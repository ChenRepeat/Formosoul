<script setup>
import { onMounted, shallowRef, onUnmounted, ref, watch, computed, nextTick } from 'vue';
import axios from 'axios';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { useI18n } from 'vue-i18n';
import { useLangStore } from '@/stores/lang';
// 本地備援資料
import { nightMarketInfo } from '@/components/SurvivalGuides/MapTWNightMarketLocation.js'

const langStore = useLangStore();
const { locale } = useI18n({ useScope: 'global' });

// --- 狀態變數 ---
const mapContainer = shallowRef(null);
const map = shallowRef(null);
const markersGroup = L.layerGroup(); // 放紅點用

const currentSelectedMarket = ref(null);
const searchKeyword = ref('');
const selectedRegion = ref('all');
const regions = ['all', 'north', 'central', 'south', 'east'];
const sheetRegionMapping = {
  north: 'North',
  central: 'Central',
  south: 'South',
  east: 'East'
};

// 抓取GOOGLE SHEET 資料
const sheetData = ref([]); // 原始資料
const API_URL = 'https://script.google.com/macros/s/AKfycbxt1vzoKcxBwO0jE-uV1hvHBTU5FuKoxQgB3Nbr76Wxqk-GX2tAVfIYNJ2ffyGmShw/exec'


// --- 3. 設定檔 ---
const IMAGE_URL = '/tjd103/SurvivalGuide/taiwan_image2_nobg.png';
const GEOJSON_URL = 'https://raw.githubusercontent.com/ronnywang/twgeojson/master/twcounty2010.4.json';
const MAP_BOUNDS = [[21.525, 119.459655], [25.615, 122.490]];
const MAX_BOUNDS = L.latLngBounds(MAP_BOUNDS).pad(1.0);

const filteredMarket = computed(()=>{
  return sheetData.value.filter( item => {
    const isZh = locale.value.includes('zh');
    const name = isZh ? item.name : (item.name_en || item.name);
    const famous = isZh ? item.famous : (item.famous_en || item.famous);

    const targetRegionValue = sheetRegionMapping[selectedRegion.value];
    const matchRegion = selectedRegion.value === 'all' || item.region === targetRegionValue;

    const matchKeyword = name.includes(searchKeyword.value) || famous.includes(searchKeyword.value);
    
    return matchRegion && matchKeyword;
  });
});

const openMyPopup = (item) => {
  const isZh = locale.value.toLowerCase().includes('zh');
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
        <div style="font-size: 12px; color: #636e72;">${displayFamous}</div>
      </div>
    `)
    .openOn(map.value);
};

const selectMarket = (item) => {
  currentSelectedMarket.value = item.name;

  map.value.flyTo([item.lat, item.lng], 9, {
    animate: true,
    duration: 1.2,
    noMoveStart: true
  });
  
  openMyPopup(item);

  // 自動捲動側邊欄列表
  nextTick(() => {
    const target = document.getElementById(`market-card-${item.name}`);
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });
};

const renderMarkers = () => {
  if (!map.value || sheetData.value.length === 0) return;

  console.log("正在重畫地標，當前語系:", langStore.locale);

  // 先把舊的紅點全部清掉
  markersGroup.clearLayers();

  filteredMarket.value.forEach(item => {
      const marker = L.circleMarker([item.lat, item.lng], {
        radius: 8,
        fillColor: '#ff4757',
        color: '#fff',
        weight: 2,
        fillOpacity: 1,
        zIndexOffset: 1000
      });

      marker.on('click', function(e) {
        L.DomEvent.stopPropagation(e);
        selectMarket(item);
      });

      markersGroup.addLayer(marker);

      if (currentSelectedMarket.value === item.name) {
        openMyPopup(item);
      }
  });
};

watch([() => langStore.locale, filteredMarket], () => {
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

const zoomIn = () => map.value?.zoomIn();
const zoomOut = () => map.value?.zoomOut();

onMounted(async () => {
  if (!mapContainer.value) return;

  document.body.style.overflow = 'hidden';
  document.documentElement.style.overflow = 'hidden';

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
  } catch (e) {console.error("GeoJSON error", e);}
  await fetchData();
  renderMarkers();
  window.addEventListener('keydown', handleKey);
});

const handleKey = (e) => {
  if (e.code === 'Escape') currentSelectedMarket.value = null;
};

onUnmounted(() => {

  document.body.style.overflow = '';
  document.documentElement.style.overflow = '';
  
  if (map.value) map.value.remove();
  window.removeEventListener('keydown', handleKey);
});

</script>

<template>
  <div class="main-layout">
    <aside class="sidebar">
      <div class="sidebar-header">
        <h2>{{ $t('nightmarket.items.map.sidebarTitle') }}</h2>

        <div class="filter-group">
          <label>{{ $t('nightmarket.items.map.filterRegion') }}</label>
          <div class="pills">
            <button 
            v-for="key in regions" 
            :key="key" 
            :class="{ active: selectedRegion === key}"
            @click="selectedRegion = key"
            >  
            <span v-if="selectedRegion === key" class="dot">●</span>
            {{ $t(`nightmarket.items.map.regions.${ key }`) }}
            </button>
          </div>
        </div>

        <div class="search-box">
          <input type="text" v-model="searchKeyword" :placeholder="$t('nightmarket.items.map.searchPlaceholder')">
          <span class="search-icon">🔍</span>
        </div>
      </div>
      <div class="sidebar-content">
        <div v-if="filteredMarket.length === 0" class="no-result">{{ $t('nightmarket.items.map.noResultFound') }}</div>

        <div v-for="item in filteredMarket" 
        :key="item.name"
        :id="`market-card-${item.name}`"
        class="market-card"
        :class="{ 'active': currentSelectedMarket === item.name }"
        @click="selectMarket(item)"
        >
        <div class="card-header">
            <span class="region-badge" v-if="item.region">{{ $t(`nightmarket.items.map.regions.${item.region.toLowerCase()}`) }}</span>
            <h3>{{ locale.includes('zh') ? item.name : (item.name_en || item.name) }}</h3>
          </div>
          <div class="card-body">
            <p class="hours">🕒 {{ locale.includes('zh') ? item.hours : (item.hours_en || item.hours) }}</p>
            <p class="note">🍴 {{ locale.includes('zh') ? item.famous : (item.famous_en || item.famous) }}</p>
          </div>
        </div>
      </div>
    </aside>

  <div class="map-wrapper">
      <div ref="mapContainer" class="map"></div>
      
      <div class="custom-zoom-control">
        <button @click="zoomIn" class="zoom-btn">+</button>
        <button @click="zoomOut" class="zoom-btn">−</button>
      </div>
    </div>
  </div>

</template>



<style scoped lang="scss">
/* 佈局設定 */
.main-layout {
  display: flex;
  width: 100%;
  height: 100vh;
  background-color: #1A1A1A;
  font-family: 'Microsoft JhengHei', sans-serif;
  overflow: hidden;
}

.sidebar {
  width: 360px;
  background: #191b31;
  display: flex;
  flex-direction: column;
  box-shadow: 4px 0 15px rgba(0,0,0,0.4);
  z-index: 10;
}

.sidebar-header {
  padding: 20px;
  background: #202442;
  border-bottom: 1px solid #2f3455;
}

.sidebar-header h2 { font-size: 1.2rem; color: #fff; margin-bottom: 15px; }

.filter-group label { color: #a0aec0; font-size: 0.9rem; }
.pills { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 8px; margin-bottom: 15px; }
.pills button {
  background: transparent; border: 1px solid #4a5568; color: #cbd5e0;
  border-radius: 20px; padding: 4px 12px; cursor: pointer; transition: 0.3s;
}
.pills button.active { background: #fff; color: #1a202c; border-color: #fff; font-weight: bold; }
.dot { font-size: 0.6rem; color: #3182ce; margin-right: 4px; }

.search-box { position: relative; }
.search-box input {
  width: 100%; padding: 10px 15px; border-radius: 8px;
  background: #2d3748; border: 1px solid #4a5568; color: white;
}
.search-icon { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #a0aec0; }

.sidebar-content { flex: 1; overflow-y: auto; padding: 15px; scroll-behavior: smooth; }
.sidebar-content::-webkit-scrollbar { width: 6px; }
.sidebar-content::-webkit-scrollbar-thumb { background: #4a5568; border-radius: 3px; }

.market-card {
  background: white; color: #333; border-radius: 10px; padding: 15px;
  margin-bottom: 15px; cursor: pointer; border-left: 5px solid transparent;
  transition: transform 0.2s;
}
.market-card:hover { transform: translateX(5px); }
.market-card.active { border-left-color: #ff4757; background: #fff5f5; box-shadow: 0 4px 12px rgba(255, 71, 87, 0.4); }

.card-header { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.card-header h3 { margin: 0; font-size: 1.1rem; }
.region-badge { background: #3182ce; color: white; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; }

.map-wrapper { flex: 1; position: relative; }
.map { width: 100%; height: 100%; }
.custom-zoom-control { position: absolute; top: 20px; left: 20px; z-index: 1000; display: flex; flex-direction: column; gap: 5px; }
.zoom-btn { width: 40px; height: 40px; background: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1.5rem; }

.no-result {
  color: $color-fsWhite;
}

</style>