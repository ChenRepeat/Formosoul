<script setup>
import { onMounted, shallowRef, onUnmounted, ref, watch, computed, nextTick } from 'vue';
import axios from 'axios';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import gsap from 'gsap';
import { useI18n } from 'vue-i18n';
import { useLangStore } from '@/stores/lang';
// 本地備援資料
import { nightMarketInfo } from '@/components/SurvivalGuides/MapTWNightMarketLocation.js'
import { LocationInformation } from '@element-plus/icons-vue';

let isSelecting = false;

// 新增：動態定位
const mapWrapperLocationRef = ref()
const globalZoomRef = ref()
const mapLeft = ref(470)
const mapTop = ref(120)

const updateZoomPosition = () => {
  if (mapWrapperLocationRef.value && globalZoomRef.value) {
    const rect = mapWrapperLocationRef.value.getBoundingClientRect()
    mapLeft.value = rect.left + 20  
    mapTop.value = rect.top + 20   
  }
}

// 回到初始狀態
const resetView = () => {
  searchKeyword.value = '';
  selectedRegion.value = 'all';
  currentSelectedMarket.value = null;

  map.value?.closePopup();
  
  weatherStatus.value = null;
  clearWeatherAnimation();

  map.value?.flyTo([24, 121.0], 7.5, {
    animate: true,
    duration: 1
  });

  // 列表回頂端
  const sidebarContent = document.querySelector('.sidebar-content');
  if (sidebarContent) {
    sidebarContent.scrollTo({ top: 0, behavior: 'smooth' });
  }

  if (mapWrapperRef.value) {
    mapWrapperRef.value.style.filter = "brightness(1) saturate(1)";
  }
};


// 抓取天氣API
const weatherStatus = ref(null) // 預設晴天

// language 
const langStore = useLangStore();
const { locale } = useI18n({ useScope: 'global' });

// 狀態變數
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

// 抓取GOOGLE SHEET MAP LOCATION資料
const sheetData = ref([]); // 原始資料
const API_URL = 'https://script.google.com/macros/s/AKfycbxt1vzoKcxBwO0jE-uV1hvHBTU5FuKoxQgB3Nbr76Wxqk-GX2tAVfIYNJ2ffyGmShw/exec'


// 設定檔
const IMAGE_URL = '/tjd103/SurvivalGuide/taiwan_image2_nobg.png';
const GEOJSON_URL = 'https://raw.githubusercontent.com/ronnywang/twgeojson/master/twcounty2010.4.json';
const MAP_BOUNDS = [[21.525, 119.459655], [25.615, 122.490]];
const MAX_BOUNDS = L.latLngBounds(MAP_BOUNDS).pad(1.0);

const filteredMarket = computed(()=>{
  // 如果搜尋關鍵字是空的，就顯示所有符合區域的
  const keyword = searchKeyword.value.trim().toLowerCase();
  // 區域篩選值
  const targetRegionValue = sheetRegionMapping[selectedRegion.value];

  return sheetData.value.filter( item => {
    // 區域過濾
    const matchRegion = selectedRegion.value === 'all' || item.region === targetRegionValue;
    // 關鍵字過濾：不論介面語系，同時比對中英文的「名稱」與「小吃」
    // 只要其中一個欄位包含關鍵字，就回傳 true
    const matchKeyword = !keyword || 
    (item.name && item.name.toLowerCase().includes(keyword)) ||
    (item.name_en && item.name_en.toLowerCase().includes(keyword)) ||
    (item.famous && item.famous.toLowerCase().includes(keyword)) ||
    (item.famous_en && item.famous_en.toLowerCase().includes(keyword));
    
    // const isZh = locale.value.includes('zh');
    // const name = isZh ? item.name : (item.name_en || item.name);
    // const famous = isZh ? item.famous : (item.famous_en || item.famous);
    return matchRegion && matchKeyword;
  });
});

const openMyPopup = (item) => {
  const isZh = langStore.locale.toLowerCase().includes('zh');
  const displayName = isZh ? item.name : (item.name_en || item.name);
  const displayHours = isZh ? item.hours : (item.hours_en || item.hours);
  const displayFamous = isZh ? item.famous : (item.famous_en || item.famous);

  L.popup({
    autoPan: true,
    autoPanPadding: [50, 50],
    offset: [0, 80],
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
  if (currentSelectedMarket.value === item.name) {
    resetView();
    return;
  }

  isSelecting = true;
  currentSelectedMarket.value = item.name;

  map.value.flyTo([item.lat, item.lng], 9, {
    animate: true,
    duration: 1.2,
    noMoveStart: true
  });

  setTimeout(() => {
    openMyPopup(item);
    isSelecting = false;
  }, 250);
  
  // openMyPopup(item);

  // 自動捲動側邊欄列表
  nextTick(() => {
    const target = document.getElementById(`market-card-${item.name}`);
    if (target) {
      target.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
  });

  // 觸發天氣的更新
  fetchWeather(item.cityName, item.locationName);

  // setTimeout(() => { isSelecting = false; }, 300);
};

const renderMarkers = () => {
  if (!map.value || sheetData.value.length === 0) return;

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

      // if (currentSelectedMarket.value === item.name) { openMyPopup(item); }
  });
};

watch([() => langStore.locale, filteredMarket], async () => {
    renderMarkers();
    if (currentSelectedMarket.value) {
        // 等待 Vue 完成語系切換的響應式更新
        await nextTick();

        map.value.closePopup();
    }
}, { deep: true });

// 監聽搜尋和過濾變化，清除不存在的選中項
watch([searchKeyword, selectedRegion, filteredMarket], () => {
  if (currentSelectedMarket.value) {
    const isCurrentInList = filteredMarket.value.some(
      item => item.name === currentSelectedMarket.value
    );
    if (!isCurrentInList) {
      currentSelectedMarket.value = null;
      map.value?.closePopup();
      weatherStatus.value = null;
    }
  }
});

// fetch Night Market Location
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

// fetch Weather API 
// 縣市對應的 API 代碼
const cityAPIMapping = {
  '臺北市': 'F-D0047-063',
  '新北市': 'F-D0047-071',
  '桃園市': 'F-D0047-007',
  '臺中市': 'F-D0047-075',
  '臺南市': 'F-D0047-079',
  '高雄市': 'F-D0047-067',
  '宜蘭縣': 'F-D0047-003',
  '新竹縣': 'F-D0047-011',
  '新竹市': 'F-D0047-024',
  '苗栗縣': 'F-D0047-015',
  '彰化縣': 'F-D0047-019',
  '南投縣': 'F-D0047-023',
  '雲林縣': 'F-D0047-027',
  '嘉義縣': 'F-D0047-031',
  '嘉義市': 'F-D0047-059',
  '屏東縣': 'F-D0047-035',
  '臺東縣': 'F-D0047-039',
  '花蓮縣': 'F-D0047-043',
  '基隆市': 'F-D0047-051'
};


// 箭頭 el => el.elementName === 'Wx' , 等於傳統函式運算式 function(el) {return el.elementName === 'Wx';}, // 也可以寫成這種形式(el) => {  return el.elementName === 'Wx';}

const fetchWeather = async (cityName, locationName) => {
  try{
    const API_KEY = 'CWA-D26EA836-2BC9-4A31-9E2D-40DC5D015EDF';
    if (!cityName || !locationName) return;

    const apiCode = cityAPIMapping[cityName];
    if (!apiCode) {
        console.warn(`[Weather] 找不到 ${cityName} 的 API 代碼`);
        return;
    }

    const url = `https://opendata.cwa.gov.tw/api/v1/rest/datastore/${apiCode}?Authorization=${API_KEY}`;
    const res = await axios.get(url)

    console.log(`[Weather API] 正在查詢：${cityName} - ${locationName}`);

    const locations = res.data?.records?.Locations?.[0]?.Location;
    if (!locations || locations.length === 0) {
        console.warn(`[Weather] API 回應中沒有找到地點資料`);
        return;
    }

    const locationData = locations.find(loc => loc.LocationName === locationName);
    if (!locationData) {
        console.warn(`[Weather] 找不到 ${locationName} 的資料`);
        console.warn(`[Weather] 可用的區域：`, locations.map(l => l.LocationName));
        return;
    }
    console.log(`[Weather] 找到資料：${locationName}`);

    let wx = locationData.WeatherElement?.find(el => el.ElementName === 'Wx');    
    if (!wx) {
        wx = locationData.WeatherElement?.find(el => el.ElementName === '天氣現象');
    }    
    if (!wx) {
        wx = locationData.WeatherElement?.find(el => el.ElementName === '天氣預報綜合描述');
    }
    if (!wx || !wx.Time || wx.Time.length === 0) {
        console.warn(`[Weather] 找不到天氣資料`);
        return;
    }

    // 天氣描述
    let weatherText = wx.Time[0]?.ElementValue?.[0]?.Weather;
    if (!weatherText) {
        weatherText = wx.Time[0]?.ElementValue?.[0]?.Value;
    }
    if (!weatherText) {
        console.warn(`[Weather] 無法取得天氣描述`);
        return;
    }
    
    // 觸發動畫
    const lowerWeatherText = weatherText.toLowerCase();

    if (weatherText.includes('雨') || weatherText.includes('雷') || weatherText.includes('陣雨')){
      weatherStatus.value = 'Rainy';
      // console.log(`✅ ${locationName} 天氣：${weatherText} → 雨天`);
    } else if (weatherText.includes('晴') || weatherText.includes('sun') || lowerWeatherText.includes('clear')) {      weatherStatus.value = 'Clear';
      // console.log(`✅ ${locationName} 天氣：${weatherText} → 晴天`);
    } else if (weatherText.includes('陰') || weatherText.includes('多雲')) {
      weatherStatus.value = 'Cloudy';
      // console.log(`✅ ${locationName} 天氣：${weatherText} → 陰天`);
    } else {
      weatherStatus.value = null;
    }
  } catch (err) { 
    console.error("天氣抓取失敗", err);
  }
};

// 天氣動畫系統
const mapWrapperRef = ref(null);
const weatherAnimationRef = ref(null);
let raindrops = [];
let clouds = [];
let sunrays = [];

// 雨滴動畫
const createRainAnimation = ()=> {
  if (!weatherAnimationRef.value) return;
  
  // clean 舊的動畫
  weatherAnimationRef.value.innerHTML = '';
  raindrops = [];

  // create 100 raindrops
  for(let i=0; i< 100; i++){
    const drop = document.createElement('div'); 
    drop.className = 'raindrop';
    drop.style.left = `${Math.random() * 100}%`;
    drop.style.animationDelay = `${Math.random() * 2}s`;
    drop.style.animationDuration = `${0.5 + Math.random() * 0.5}s`;
    weatherAnimationRef.value.appendChild(drop); // 放進drop這個盒子
    raindrops.push(drop);
  }  
};

  // create cloud animation
  const createCloudAnimation = () => {
  if (!weatherAnimationRef.value) return;
    weatherAnimationRef.value.innerHTML = '';
    clouds = [];

    // 10 clouds
    for(let i = 0; i < 10; i++){
      const cloud = document.createElement('div');
      cloud.className = 'cloud';
      cloud.style.top = `${10 + Math.random() * 80}%`;
      cloud.style.animationDelay = `${Math.random() * 5}s`;
      cloud.style.animationDuration = `${20 + Math.random() * 10}s`;

      // 雲朵內容 (用多個 span 組成雲的形狀)
      cloud.innerHTML = '☁️';
      cloud.style.fontSize = `${40 + Math.random() * 40}px`;

      weatherAnimationRef.value.appendChild(cloud);
      clouds.push(cloud);
    }
  };

  // 晴天動畫
  const createSunnyAnimation = () => {
    if(!weatherAnimationRef.value) return
    weatherAnimationRef.value.innerHTML = '';
    sunrays = [];

    for (let i = 0; i < 6; i++){
      const ray = document.createElement('div');
      ray.className = 'sunray';
      ray.style.left = `${0 + i * 15}%`;
      ray.style.animationDelay = `${i * 0.3}s`;
      weatherAnimationRef.value.appendChild(ray);
      sunrays.push(ray);
    }
    // 漂浮光點
    for(let i = 0; i<20; i++){
      const particle = document.createElement('div');
      particle.className = 'sun-particle';
      particle.style.left = `${Math.random() * 100}%`;
      particle.style.top = `${Math.random() * 100}%`;
      particle.style.animationDelay = `${Math.random() * 3}s`;
      particle.style.animationDuration = `${3 + Math.random() * 2}s`;

      weatherAnimationRef.value.appendChild(particle);
      sunrays.push(particle);
    }
  };
  // 清除動畫
  const clearWeatherAnimation = () => {
    if (!weatherAnimationRef.value) return;
    weatherAnimationRef.value.innerHTML = '';
    raindrops = [];
    clouds = [];
  };

// 監聽天氣變化
watch(weatherStatus, (newStatus, oldStatus)=>{
  if (!mapWrapperRef.value) return;
  console.log(`[Animation] 執行 ${newStatus} 效果`);

  // 避免初始化時不必要的動畫
  if (!oldStatus) {
    console.log(`[Animation] 初始化天氣：${newStatus}`);
  } else {
    console.log(`[Animation] 天氣變化：${oldStatus} → ${newStatus}`);
  }

  const filters = {
    Rainy: "brightness(0.6) saturate(0.7) hue-rotate(15deg)",
    Cloudy: "brightness(0.8) saturate(0.85)",
    Clear: "brightness(1) saturate(1)"
  };

  const targetFilter = filters[newStatus] || filters.Clear;

  // 天氣 GSAP 濾鏡動畫
  gsap.to(mapWrapperRef.value, {
    duration: 1.5,
    filter: targetFilter,
    ease: "power2.inOut",
  });

  // 根據天氣切換動畫
  switch(newStatus) {
    case 'Rainy':
      createRainAnimation();
      break;
    case 'Cloudy':
      createCloudAnimation();
      break;
    case 'Clear':  
      createSunnyAnimation();
      break;
  }
});


const zoomIn = () => {
  map.value?.setZoom(map.value.getZoom() + 1);
}
const zoomOut = () => {
  map.value?.setZoom(map.value.getZoom() - 1)
}
const handleKey = (e) => {
  const isModalOpen = document.querySelector('.card-modal');
  if (e.code === 'Escape' && !isModalOpen) {
    resetView();
  }
};

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
    opacity: 0.8,
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
        const countyName = feature.properties?.COUNTYNAME;
        
        if (countyName) {
          // 檢查這個縣市是否有夜市資料
          const hasNightMarket = sheetData.value.some(market => 
            market.cityName === countyName
          );
          
          if (hasNightMarket) {
            // 只有有夜市的縣市才綁定互動效果
            layer.bindTooltip(countyName, { 
              className: 'county-label', 
              direction: 'center',
              permanent: false 
            });
            
            layer.on('mouseover', () => {
              layer.setStyle({ 
                color: '#00ffcc', 
                opacity: 1, 
                weight: 2 
              });
            });
            layer.on('mouseout', () => {
              layer.setStyle({ 
                color: 'transparent', 
                opacity: 0, 
                weight: 1 
              });
            });
          }
        }
      }
    }).addTo(map.value);
  } catch (e) {console.error("GeoJSON error", e);}
//       onEachFeature: (feature, layer) => {
//         // 綁定 Tooltip
//         if (feature.properties?.COUNTYNAME) {
//           layer.bindTooltip(feature.properties.COUNTYNAME, { 
//             className: 'county-label', 
//             direction: 'center',
//             permanent: false 
//           });
//         }
//         // 滑鼠移入高亮 (如果過濾成功，這裡就不會對外島生效)
//         layer.on('mouseover', () => layer.setStyle({ color: '#00ffcc', opacity: 1, weight: 2 }));
//         layer.on('mouseout', () => layer.setStyle({ color: 'transparent', opacity: 0, weight: 1 }));
//       }
//     }).addTo(map.value);
//   } catch (e) {console.error("GeoJSON error", e);}
//   nextTick(() => {
//     // 這裡呼叫一次確保初始的 'Clear' 效果有被套用
//     gsap.set(mapWrapperRef.value, { filter: "brightness(1) saturate(1) hue-rotate(0deg)" });
//   });
//   await fetchData();
//   renderMarkers();
//   window.addEventListener('keydown', handleKey);
// });
  nextTick(() => {
    // 這裡呼叫一次確保初始的 'Clear' 效果有被套用
    gsap.set(mapWrapperRef.value, { filter: "brightness(1) saturate(1) hue-rotate(0deg)" });
  });

  await fetchData();
  renderMarkers();
  window.addEventListener('keydown', handleKey);

  map.value.on('popupclose', () => {
    // 只有在「不是因為切換夜市」且「目前有選中項目」的情況下，才執行重置
    if (!isSelecting && currentSelectedMarket.value) {
      resetView();
    }
  });
});

onMounted(() => {
  updateZoomPosition()
  window.addEventListener('resize', updateZoomPosition)
  window.addEventListener('scroll', updateZoomPosition)
})

onUnmounted(() => {

  document.body.style.overflow = '';
  document.documentElement.style.overflow = '';
  
  if (map.value) map.value.remove();
  window.removeEventListener('keydown', handleKey);

  window.removeEventListener('resize', updateZoomPosition)
  window.removeEventListener('scroll', updateZoomPosition)
});

defineExpose({
  zoomIn,zoomOut
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

  <div ref="mapWrapperRef" class="map-wrapper" :class="`weather-${weatherStatus}`">
    <div ref="mapContainer" class="map"></div>
    
    
      <!-- 天氣動畫用的div -->
      <div ref="weatherAnimationRef" class="weather-animation-layer"></div>

      <!-- 天氣狀態顯示 -->
      <div class="weather-status-display" v-if="currentSelectedMarket">
        <div class="weather-icon">
          {{ weatherStatus === 'Rainy' ? '🌧️' : weatherStatus === 'Cloudy' ? '☁️' : '☀️' }}
        </div>
        <div class="weather-text">
          {{ weatherStatus === 'Rainy' ? 'Rainy' : weatherStatus === 'Cloudy' ? 'Cloudy' : 'Clear' }}
        </div>
        <button 
          class="clear-selection-btn" 
          @click="resetView"
          title="Clear Selection (ESC)"
        >
          ✕
        </button>
      </div>
    </div>
  </div>

</template>

    <!-- :style="{ left: mapLeft + 'px', top: mapTop + 'px' }" -->


<style scoped lang="scss">

// .custom-zoom-control { position: absolute; top: 20px; left: 20px; z-index: 1000; display: flex; flex-direction: column; gap: 5px; }
// .zoom-btn { width: 40px; height: 40px; background: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1.5rem; }




/* 佈局設定 */
.main-layout {
  display: flex;
  width: 100%;
  height: 100vh;
  background-color: #1A1A1A;
  overflow: hidden;
}

.sidebar {
  width: 360px;
  background: #191b31;
  display: flex;
  flex-direction: column;
  height: 100vh;
  max-height: 100vh;
  box-shadow: 4px 0 15px rgba(0,0,0,0.4);
  z-index: 10;
  overflow: hidden;
}

.sidebar-header {
  padding: 20px;
  background: #202442;
  border-bottom: 1px solid #2f3455;
  flex-shrink: 0;
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

.sidebar-content { 
  flex: 1; 
  overflow-y: auto; 
  padding: 15px; 
  scroll-behavior: smooth; 
  min-height: 0;
}
.sidebar-content::after {
  content: "";
  display: block;
  height: 80px;          
  width: 100%;
}

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

.map-wrapper { 
  flex: 1; 
  position: relative; 
  will-change: filter;
  backface-visibility: hidden; 
  overflow: hidden;
  background: #0A3D70;
}

.map { 
  width: 100%; 
  height: 100%;
  background: transparent !important; 

  :deep(.fixed-image-layer) {
  filter: brightness(0.8); 
}
}

.no-result {
  color: $color-fsWhite;
}

// KEY: 使用:deep()穿透scoped，只影響這個組件內的動畫
:deep(.weather-animation-layer) {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1000;
  overflow: hidden;
}

:deep(.raindrop) {
  position: absolute;
  width: 3px;
  height: 40px;
  background: linear-gradient(to bottom, transparent, rgba(100, 150, 255, 0.9));
  animation: fall linear infinite;
  top: -50px;
  z-index: 1001;
}

@keyframes fall {
  0% {
    transform: translateY(0);
    opacity: 1;
  }
  100% {
    transform: translateY(calc(100vh + 50px));
    opacity: 0.5;
  }
}

:deep(.cloud) {
  position: absolute;
  left: -100px;
  animation: float linear infinite;
  opacity: 0.8;
  filter: drop-shadow(2px 2px 6px rgba(0,0,0,0.3));
  z-index: 1001;
}

@keyframes float {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(calc(100vw + 100px));
  }
};

:deep(.sunray) {
  position: absolute;
  top: -100px;
  width: 22%;
  height: 80%;
  background: linear-gradient(to bottom, 
    rgba(255, 230, 100, 0.6), 
    rgba(255, 200, 50, 0.4),
    rgba(255, 180, 30, 0.2), 
    transparent);
  transform-origin: top center;
  animation: sunrayMove 4s ease-in-out infinite;
  z-index: 999;
}

@keyframes sunrayMove {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
    opacity: 0.7;
  }
  50% {
    transform: translateY(80px) rotate(3deg);
    opacity: 0.9;
  }
}

:deep(.sun-particle) {
  position: absolute;
  width: 15px;
  height: 15px;
  background: radial-gradient(circle, rgba(255, 220, 100, 1),
  rgba(255, 200, 80, 0.6),
   transparent);
  border-radius: 50%;
  animation: particleFloat ease-in-out infinite;
  z-index: 999;
  box-shadow: 0 0 15px rgba(255, 220, 100, 0.8);
}

@keyframes particleFloat {
  0%, 100% {
    transform: translateY(0) scale(1);
    opacity: 0.6;
  }
  50% {
    transform: translateY(-30px) scale(1.4);
    opacity: 1;
  }
};


</style>