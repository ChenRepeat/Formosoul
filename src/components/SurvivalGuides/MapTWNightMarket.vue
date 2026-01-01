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

const mapContainer = shallowRef(null);
const map = shallowRef(null);
const currentSelectedMarket = ref(null);

const IMAGE_URL = '/tjd103/SurvivalGuide/taiwan_image2_nobg.png';
const GEOJSON_URL = 'https://raw.githubusercontent.com/ronnywang/twgeojson/master/twcounty2010.4.json';

// 座標設定
const MAP_BOUNDS = [
  [21.525, 119.459655],
  [25.615000000000002, 122.49034499999999]
];
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
  try{ // 單純抓取資料，所以用get就好，不用用到post
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
  position: relative;
  background-color: #1a1d2e;
  overflow: hidden;
}

.map {
  width: 100%;
  height: 100%;
  z-index: 1;
  background: transparent;
}

:deep(.leaflet-image-layer),
:deep(.leaflet-zoom-animated) {
  transition: none !important;
  will-change: transform;
}

:deep(.fixed-image-layer) {
  image-rendering: -webkit-optimize-contrast;
}

:deep(.county-label) {
  background: transparent;
  border: none;
  box-shadow: none;
  color: #fff;
  font-weight: bold;
  font-size: 14px;
  text-shadow: 1px 1px 2px #000;
}

:deep(.leaflet-popup-content-wrapper) {
  border-radius: 8px;
  padding: 5px;
  box-shadow: 0 3px 10px rgba(0,0,0,0.3);
}
</style>