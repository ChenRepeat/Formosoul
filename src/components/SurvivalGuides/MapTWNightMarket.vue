<script setup>
import { onMounted, shallowRef, onUnmounted, ref, watch, computed, nextTick } from 'vue';
import axios from 'axios';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { useI18n } from 'vue-i18n';
import { useLangStore } from '@/stores/lang';

const langStore = useLangStore();
const { locale } = useI18n({ useScope: 'global' });
const markersGroup = L.layerGroup(); // 放紅點用

// 抓取GOOGLE SHEET 資料
const sheetData = ref([]);
const API_URL = 'https://script.google.com/macros/s/AKfycbxt1vzoKcxBwO0jE-uV1hvHBTU5FuKoxQgB3Nbr76Wxqk-GX2tAVfIYNJ2ffyGmShw/exec'

// --- 1. 資料設定 (維持原案) ---
const tasks = ref([
  { id: 1, name: '台北士林夜市', region: '北部', lat: 25.0878, lng: 121.5241, hours: '每日 16:00 - 00:00', note: '豪大大雞排、士林大香腸' },
  { id: 2, name: '饒河街觀光夜市', region: '北部', lat: 25.0501, lng: 121.5777, hours: '每日 17:00 - 00:00', note: '福州世祖胡椒餅、陳董藥燉排骨' },
  { id: 3, name: '寧夏觀光夜市', region: '北部', lat: 25.0560, lng: 121.5153, hours: '每日 17:00 - 01:00', note: '圓環邊蚵仔煎、劉芋仔蛋黃芋餅' },
  { id: 4, name: '臨江街夜市 (通化)', region: '北部', lat: 25.0306, lng: 121.5543, hours: '每日 18:00 - 00:00', note: '御品元冰火湯圓、紅花麻辣鹽水雞' },
  { id: 5, name: '萬華華西街夜市', region: '北部', lat: 25.0368, lng: 121.4996, hours: '每日 16:00 - 00:00', note: '阿義魯肉飯、小王煮瓜' },
  { id: 6, name: '南機場夜市', region: '北部', lat: 25.0239, lng: 121.5052, hours: '每日 17:00 - 00:00', note: '阿男麻油雞、來來水餃' },
  { id: 7, name: '大龍峒夜市', region: '北部', lat: 25.0734, lng: 121.5165, hours: '每日 16:00 - 00:00', note: '大龍峒肉羹、郭記大塊肉羹' },
  { id: 8, name: '延三夜市', region: '北部', lat: 25.0664, lng: 121.5126, hours: '每日 18:00 - 00:00', note: '施家鮮肉湯圓、大橋頭老牌筒仔米糕' },
  { id: 9, name: '台中逢甲夜市', region: '中部', lat: 24.1802, lng: 120.6450, hours: '每日 16:00 - 02:00', note: '明倫蛋餅、日船章魚小丸子' },
  { id: 10, name: '花蓮東大門夜市', region: '東部', lat: 23.9745, lng: 121.6115, hours: '每日 17:00 - 00:00', note: '第一家烤肉、林記燒番麥' },
  { id: 11, name: '高雄瑞豐夜市', region: '南部', lat: 22.6661, lng: 120.2998, hours: '週二、四、五、六、日', note: '天使雞排、萬國牛排' },
  { id: 12, name: '台南花園夜市', region: '南部', lat: 23.0105, lng: 120.1983, hours: '週四、六、日', note: '二師兄滷味、紅妃大腸香腸' }
]);

// --- 2. 狀態變數 ---
const mapContainer = shallowRef(null);
const map = shallowRef(null);
const currentSelectedMarket = ref(null);

// --- 3. 設定檔 ---
const IMAGE_URL = '/tjd103/SurvivalGuide/taiwan_image2_nobg.png';
const GEOJSON_URL = 'https://raw.githubusercontent.com/ronnywang/twgeojson/master/twcounty2010.4.json';
const MAP_BOUNDS = [[21.525, 119.459655], [25.615, 122.490]];
const MAX_BOUNDS = L.latLngBounds(MAP_BOUNDS).pad(1.0);

// 夜市資料
// const tasks = ref(nightMarketInfo)
  
const renderMarkers = () => {
  if (!map.value || sheetData.value.length === 0) return;

  console.log("正在重畫地標，當前語系:", langStore.locale);

  // 先把舊的紅點全部清掉
  markersGroup.clearLayers();

  sheetData.value.forEach(item => {
      const marker = L.circleMarker([item.lat, item.lng], {
        radius: 8,
        fillColor: '#ff4757',
        color: '#fff',
        weight: 2,
        fillOpacity: 1,
        zIndexOffset: 1000
      });

  const openMyPopup = () => {
      const isZh = langStore.locale.toLowerCase().includes('zh');
      const displayName = isZh ? item.name : (item.name_en || item.name);
      const displayHours = isZh ? item.hours : (item.hours_en || item.hours);
      const displayFamous = isZh ? item.famous : (item.famous_en || item.famous);

      L.popup({
        autoPan: false,
        offset: [0, -10],
        closeButton: true,
        className: 'custom-popup'
      })
        .setLatLng([item.lat, item.lng])
        .setContent(`
          <div style="text-align: center; min-width: 150px;">
            <h3 style="margin: 0 0 5px 0; color: #d63031;">${displayName}</h3>
            <div style="font-size: 13px; color: #333; margin-bottom: 5px;">
              <strong>🕒 ${displayHours}</strong>
            </div>
            <div style="font-size: 12px; color: #636e72;">
              ${displayFamous}
            </div>
          </div>
        `)
        .openOn(map.value);
    };

    marker.on('click', function(e) {
        L.DomEvent.stopPropagation(e);
        currentSelectedMarket.value = item.name; 
        map.value.flyTo([item.lat, item.lng], 9, {
          animate: true,
          duration: 1.2,
          noMoveStart: true
      });
        openMyPopup();
      });

    markersGroup.addLayer(marker);

    if (currentSelectedMarket.value === item.name) {
      openMyPopup();
    }
  });
};

watch(() => langStore.locale, (newVal, oldVal) => {
  if (map.value && newVal !== oldVal) {
    renderMarkers();
  }
});

watch(sheetData, () => {
  renderMarkers();
}, { deep: true });

const fetchData = async () => {
  try{
    const response = await axios.get(API_URL);
    if (response.data && response.data.length > 0){
      console.log("資料讀取成功：", response.data);
      sheetData.value = response.data
    } else {
      throw new Error("API 回傳資料為空");
    }
  } catch (err) {
    console.warn('API 讀取失敗或異常，自動切換至本地靜態備援資料', err);
    sheetData.value = nightMarketInfo;
  } 
};

onMounted(async () => {
  if (!mapContainer.value) return;

  // 1. 初始化地圖
  map.value = L.map(mapContainer.value, {
    center: [23.2, 121.0],
    zoom: 7.5,
    minZoom: 7.0,
    maxZoom: 20.0,
    zoomSnap: 0.5,
    zoomControl: false,
    attributionControl: false,
    maxBounds: MAX_BOUNDS, 
    maxBoundsViscosity: 0.8,
    zoomAnimation: true,
    fadeAnimation: false, 
    markerZoomAnimation: true,
    wheelDebounceTime: 60,
    wheelPxPerZoomLevel: 120,
    closePopupOnClick: false
  });

  markersGroup.addTo(map.value);

  // 2. 貼上美術圖
  L.imageOverlay(IMAGE_URL, MAP_BOUNDS, {
    opacity: 1,
    interactive: true,
    zIndex: 1,
    className: 'fixed-image-layer' 
  }).addTo(map.value);

  // 3. 疊加 GeoJSON
  try {
    const res = await fetch(GEOJSON_URL);
    const data = await res.json();
    
    L.geoJSON(data, {
      // 過濾外島
      filter: (feature) => {
        const exclude = ['金門縣', '連江縣', '澎湖縣'];
        return !exclude.includes(feature.properties.COUNTYNAME);
      },
      // 樣式設定：完全透明隱藏線條
      style: {
        fillColor: 'transparent',
        color: 'transparent', 
        weight: 1,
        opacity: 0, 
        dashArray: '3, 5'
      },
      onEachFeature: (feature, layer) => {
        // 綁定 Tooltip
        if (feature.properties?.COUNTYNAME) {
          layer.bindTooltip(feature.properties.COUNTYNAME, { 
            className: 'county-label', 
            direction: 'center',
            permanent: false 
          });
        }
        // 滑鼠移入高亮 (如果過濾成功，這裡就不會對外島生效)
        layer.on('mouseover', () => layer.setStyle({ color: '#00ffcc', opacity: 1, weight: 2 }));
        layer.on('mouseout', () => layer.setStyle({ color: 'transparent', opacity: 0, weight: 1 }));
      }
    }).addTo(map.value);
  } catch (e) {
    console.error("GeoJSON error", e);
  }
  fetchData();
});

onUnmounted(() => {
  if (map.value) {
    map.value.remove();
    map.value = null;
  }
});
</script>

<template>
  <div class="map-container">
    <div ref="mapContainer" class="map"></div>
    <div class="map-weather-left-frame">

    </div>
  </div>
  <div class="container">
    <h2>資料顯示</h2>
    <table border="1">
      <thead>
        <tr>
          <th>名稱</th>
          <th>經緯度</th>
          <th>營業時間</th>
          <th>特色小吃</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in sheetData" :key="index">
          <td>{{ locale.includes('zh') ? item.name : item.name_en }}</td>
          <td>{{ item.lat }}, {{ item.lng }}</td>
          <td>{{ locale.includes('zh') ? item.hours : item.hours_en }}</td>
          <td>{{ locale.includes('zh') ? item.famous : item.famous_en }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>



<style scoped>

.map-weather-left-frame {
    width: 35%;
    height: 100%;
    position: absolute;
    top: 0%;
    left: 0%;
}

.map-container {
  width: 100%;
  height: 100vh;
  background-color: #0f1020;
  font-family: 'Microsoft JhengHei', sans-serif;
  color: white;
  overflow: hidden; 
}

/* --- 左側 Sidebar --- */
.sidebar {
  width: 360px;
  background: #191b31;
  display: flex;
  flex-direction: column;
  box-shadow: 4px 0 15px rgba(0,0,0,0.4);
  z-index: 10;
  height: 100vh; 
}

.sidebar-header {
  padding: 20px;
  background: #202442;
  border-bottom: 1px solid #2f3455;
  flex-shrink: 0; 
}

.sidebar-header h2 {
  font-size: 1.2rem;
  margin: 0 0 15px 0;
  color: #fff;
}

.filter-group { margin-bottom: 15px; }
.filter-group label {
  font-size: 0.9rem;
  color: #a0aec0;
  margin-right: 10px;
}

.pills {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
  margin-top: 5px;
}
.pills button {
  background: transparent;
  border: 1px solid #4a5568;
  color: #cbd5e0;
  border-radius: 20px;
  padding: 4px 12px;
  font-size: 0.85rem;
  cursor: pointer;
  transition: 0.3s;
  display: flex;
  align-items: center;
  gap: 5px;
}
.pills button:hover { border-color: #63b3ed; color: white; }
.pills button.active {
  background: #fff;
  color: #1a202c;
  border-color: #fff;
  font-weight: bold;
}
.dot { font-size: 0.6rem; color: #3182ce; }

.search-box { position: relative; }
.search-box input {
  width: 100%;
  padding: 10px 35px 10px 15px;
  border-radius: 8px;
  border: 1px solid #4a5568;
  background: #2d3748;
  color: white;
  outline: none;
  box-sizing: border-box;
}
.search-box input:focus { border-color: #63b3ed; }
.search-icon {
  position: absolute;
  right: 10px;
  top: 50%;
  transform: translateY(-50%);
  color: #a0aec0;
}

.sidebar-content {
  flex: 1; 
  overflow-y: auto; 
  padding: 15px;
  padding-bottom: 120px; /* 預留底部空間防止遮擋 */
  position: relative; 
  min-height: 0; 
}

.sidebar-content::-webkit-scrollbar { width: 6px; }
.sidebar-content::-webkit-scrollbar-thumb { background: #4a5568; border-radius: 3px; }
.sidebar-content::-webkit-scrollbar-track { background: transparent; }

.no-result { text-align: center; color: #718096; margin-top: 30px; }

.market-card {
  background: white;
  color: #333;
  border-radius: 10px;
  padding: 15px;
  margin-bottom: 15px;
  cursor: pointer;
  transition: transform 0.2s;
  position: relative;
  border-left: 5px solid transparent;
}
.market-card:hover { transform: translateY(-3px); }
.market-card.active {
  border-left: 5px solid #ff4757;
  box-shadow: 0 4px 12px rgba(255, 71, 87, 0.4);
}

.card-header {
  display: flex;
  align-items: center;
  margin-bottom: 8px;
}
.card-header h3 { margin: 0 0 0 8px; font-size: 1.1rem; font-weight: bold; }
.region-badge {
  background: #3182ce;
  color: white;
  font-size: 0.7rem;
  padding: 2px 6px;
  border-radius: 4px;
}
.hours { font-size: 0.85rem; color: #555; margin: 4px 0; }
.note { font-size: 0.85rem; color: #718096; margin: 0; line-height: 1.4; }

/* --- 地圖區域 --- */
.map-wrapper { flex: 1; position: relative; }
.map { width: 100%; height: 100%; background: #0f1020; }

.custom-zoom-control {
  position: absolute;
  top: 20px;
  left: 20px;
  z-index: 1000;
  display: flex;
  flex-direction: column;
  box-shadow: 0 2px 6px rgba(0,0,0,0.3);
  border-radius: 4px;
  overflow: hidden;
}

.zoom-btn {
  width: 40px;
  height: 40px;
  background: white;
  border: none;
  font-size: 1.5rem;
  color: #4a5568;
  cursor: pointer;
  display: flex;
  justify-content: center;
  align-items: center;
  transition: background 0.2s;
}
.zoom-btn:first-child { border-bottom: 1px solid #e2e8f0; }
.zoom-btn:hover { background: #f7fafc; color: #000; }
.zoom-btn:active { background: #edf2f7; }

/* --- ★★★ 關鍵 CSS 修改：防跑版與動畫優化 ★★★ --- */

/* 1. 禁止圖片層的自動過渡，防止縮放時底圖飄移 */
:deep(.leaflet-image-layer),
:deep(.leaflet-zoom-animated) {
  transition: none !important;
  will-change: transform;
}

/* 2. 優化圖片渲染 */
:deep(.fixed-image-layer) {
  image-rendering: -webkit-optimize-contrast;
}

/* 3. Popup 樣式優化 */
:deep(.leaflet-popup-content-wrapper) {
  border-radius: 8px;
  padding: 5px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.3);
}

:deep(.county-label) {
  background: transparent; border: none; box-shadow: none;
  color: #fff; font-weight: bold; font-size: 14px; text-shadow: 1px 1px 2px #000;
}

:deep(.leaflet-marker-icon),
:deep(.leaflet-popup) {
  will-change: transform;
}
</style>