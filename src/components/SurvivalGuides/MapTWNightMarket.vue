<script setup>
import { onMounted, shallowRef, ref, onUnmounted, computed, watch, nextTick } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

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
const markerGroup = shallowRef(null);
const currentMarketId = ref(null);
const searchKeyword = ref('');
const selectedRegion = ref('全台');
const regions = ['全台', '北部', '中部', '南部', '東部'];

// --- 3. 設定檔 ---
const IMAGE_URL = '/tjd103/SurvivalGuide/taiwan_image2_nobg.png';
const GEOJSON_URL = 'https://raw.githubusercontent.com/ronnywang/twgeojson/master/twcounty2010.4.json';
const MAP_BOUNDS = [[21.525, 119.459655], [25.615, 122.490]];
const MAX_BOUNDS = L.latLngBounds(MAP_BOUNDS).pad(1.0);

// --- 4. 篩選列表 ---
const filteredTasks = computed(() => {
  return tasks.value.filter(task => {
    const matchRegion = selectedRegion.value === '全台' || task.region === selectedRegion.value;
    const matchKeyword = task.name.includes(searchKeyword.value) || task.note.includes(searchKeyword.value);
    return matchRegion && matchKeyword;
  });
});

// --- 5. 更新 Marker (修正：加入 renderer padding 防止閃爍) ---
const updateMapMarkers = (newTasks) => {
  if (!map.value || !markerGroup.value) return;

  markerGroup.value.clearLayers();

  // ★★★ 關鍵修改：建立擴大範圍的渲染器 ★★★
  // padding: 1 代表除了目前畫面，會額外預繪製 1 個螢幕寬度的範圍
  const myRenderer = L.svg({ padding: 1 });

  newTasks.forEach(task => {
    const marker = L.circleMarker([task.lat, task.lng], {
      renderer: myRenderer, // ★ 套用渲染器
      radius: 8, 
      fillColor: '#ff4757', 
      color: '#fff', 
      weight: 2, 
      fillOpacity: 1,
      zIndexOffset: 1000 // 確保在最上層
    });

    // 維持點擊互動邏輯
    marker.on('click', (e) => {
      L.DomEvent.stopPropagation(e);
      selectMarket(task);
    });

    markerGroup.value.addLayer(marker);
  });
};

watch(filteredTasks, (newTasks) => {
  updateMapMarkers(newTasks);
}, { deep: true });

// --- 6. 互動功能 (整合 Snippet B 的 flyTo 與 Popup) ---
const selectMarket = (task) => {
  currentMarketId.value = task.id;
  
  // A. 地圖互動
  if (map.value) {
    // 1. 移動地圖
    map.value.flyTo([task.lat, task.lng], 8 , { 
      animate: true, 
      duration: 1.2,
      noMoveStart: true // 減少不必要的觸發
    });

    // 2. 手動建立並開啟 Popup
    L.popup({
        autoPan: false, // 關鍵：禁止自動平移，避免畫面跳動
        offset: [0, -10],
        closeButton: false,
        className: 'custom-popup'
      })
      .setLatLng([task.lat, task.lng])
      .setContent(`
        <div style="text-align: center; min-width: 150px;">
          <h6 style="margin: 0 0 5px 0; color: #d63031;">${task.name}</h6>
        </div>
      `)
      .openOn(map.value);
  }

  // B. 側邊欄捲動
  nextTick(() => {
    const container = document.querySelector('.sidebar-content');
    const target = document.getElementById(`market-card-${task.id}`);
    
    if (container && target) {
      const containerRect = container.getBoundingClientRect();
      const targetRect = target.getBoundingClientRect();
      const currentScroll = container.scrollTop;
      const scrollTo = (targetRect.top - containerRect.top) + currentScroll - (containerRect.height / 2) + (targetRect.height / 2);

      container.scrollTo({
        top: scrollTo,
        behavior: 'smooth'
      });
    }
  });
};

const zoomIn = () => map.value && map.value.zoomIn();
const zoomOut = () => map.value && map.value.zoomOut();

onMounted(async () => {
  if (!mapContainer.value) return;

  // 初始化參數
  map.value = L.map(mapContainer.value, {
    center: [23.6, 121.0], 
    zoom: 7.5, 
    minZoom: 7.0, 
    maxZoom: 18.0,
    zoomSnap: 0.5,        
    zoomControl: false, 
    attributionControl: false,
    maxBounds: MAX_BOUNDS, 
    maxBoundsViscosity: 0.8,
    // 動畫參數
    zoomAnimation: true,
    fadeAnimation: true, 
    markerZoomAnimation: true,
    wheelDebounceTime: 60,
    // 投影設定：配合靜態圖片
    crs: L.CRS.EPSG4326 
  });

  markerGroup.value = L.layerGroup().addTo(map.value);

  L.imageOverlay(IMAGE_URL, MAP_BOUNDS, { 
    opacity: 1, interactive: true, zIndex: 1, className: 'fixed-image-layer'
  }).addTo(map.value);

  try {
    const res = await fetch(GEOJSON_URL);
    const data = await res.json();
    L.geoJSON(data, {
      filter: (f) => !['金門縣', '連江縣', '澎湖縣'].includes(f.properties.COUNTYNAME),
      style: { fillColor: 'transparent', color: 'transparent', weight: 1 },
      onEachFeature: (f, layer) => {
        if (f.properties?.COUNTYNAME) {
          layer.bindTooltip(f.properties.COUNTYNAME, { className: 'county-label', direction: 'center', permanent: false });
        }
      }
    }).addTo(map.value);
  } catch (e) { console.error(e); }

  updateMapMarkers(filteredTasks.value);
});

onUnmounted(() => {
  if (map.value) {
    map.value.remove();
    map.value = null;
  }
});
</script>

<template>
  <div class="main-layout">
    
    <aside class="sidebar">
      <div class="sidebar-header">
        <h2>地點篩選</h2>
        
        <div class="filter-group">
          <label>地區：</label>
          <div class="pills">
            <button 
              v-for="region in regions" 
              :key="region"
              :class="{ active: selectedRegion === region }"
              @click="selectedRegion = region"
            >
              <span v-if="selectedRegion === region" class="dot">●</span>
              {{ region }}
            </button>
          </div>
        </div>

        <div class="search-box">
          <input 
            type="text" 
            v-model="searchKeyword" 
            placeholder="請輸入夜市名稱..." 
          />
          <span class="search-icon">🔍</span>
        </div>
      </div>
      
      <div class="sidebar-content">
        <div v-if="filteredTasks.length === 0" class="no-result">
          沒有找到符合的夜市資料 🌙
        </div>

        <div 
          v-for="task in filteredTasks" 
          :key="task.id"
          :id="`market-card-${task.id}`"
          class="market-card"
          :class="{ 'active': currentMarketId === task.id }"
          @click="selectMarket(task)"
        >
          <div class="card-header">
            <span class="region-badge">{{ task.region }}</span>
            <h3>{{ task.name }}</h3>
          </div>
          <div class="card-body">
            <p class="hours">🕒 {{ task.hours }}</p>
            <p class="note">🍴 {{ task.note }}</p>
          </div>
        </div>
      </div>
    </aside>

    <div class="map-wrapper">
      <div ref="mapContainer" class="map"></div>

      <div class="custom-zoom-control">
        <button @click="zoomIn" class="zoom-btn" title="放大">+</button>
        <button @click="zoomOut" class="zoom-btn" title="縮小">−</button>
      </div>
    </div>

  </div>
</template>

<style scoped>
/* --- 全局佈局 --- */
.main-layout {
  display: flex;
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