<script setup>
import { onMounted, shallowRef, onUnmounted, ref } from 'vue';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';

const mapContainer = shallowRef(null);
const map = shallowRef(null);

const IMAGE_URL = '/tjd103/SurvivalGuide/taiwan_image2_nobg.png';
const GEOJSON_URL = 'https://raw.githubusercontent.com/ronnywang/twgeojson/master/twcounty2010.4.json';

// 座標設定
const MAP_BOUNDS = [
  [21.525, 119.459655],
  [25.615000000000002, 122.49034499999999]
];
const MAX_BOUNDS = L.latLngBounds(MAP_BOUNDS).pad(1.0);

// 夜市資料
const tasks = [
  { name: '台北士林夜市', lat: 25.0878, lng: 121.5241, hours: '每日 16:00 - 00:00', note: '豪大大雞排、士林大香腸' },
  { 
    name: '饒河街觀光夜市', 
    lat: 25.0501, 
    lng: 121.5777, 
    hours: '每日 17:00 - 00:00', 
    note: '福州世祖胡椒餅、陳董藥燉排骨、東發號油飯' 
  },  
  { 
    name: '寧夏觀光夜市', 
    lat: 25.0560, 
    lng: 121.5153, 
    hours: '每日 17:00 - 01:00', 
    note: '圓環邊蚵仔煎、劉芋仔蛋黃芋餅、豬肝榮仔' 
  },
  { 
    name: '臨江街夜市 (通化)', 
    lat: 25.0306, 
    lng: 121.5543, 
    hours: '每日 18:00 - 00:00', 
    note: '御品元冰火湯圓、紅花麻辣鹽水雞、駱記小炒' 
  },
  { 
    name: '萬華華西街夜市', 
    lat: 25.0368, 
    lng: 121.4996, 
    hours: '每日 16:00 - 00:00', 
    note: '阿義魯肉飯、小王煮瓜(清湯瓜仔肉)、蛇肉湯' 
  },
  { 
    name: '南機場夜市', 
    lat: 25.0239, 
    lng: 121.5052, 
    hours: '每日 17:00 - 00:00', 
    note: '阿男麻油雞、來來水餃、玉米家烤玉米' 
  },
  // { 
  //   name: '大龍峒夜市', 
  //   lat: 25.0734, 
  //   lng: 121.5165, 
  //   hours: '每日 16:00 - 00:00', 
  //   note: '大龍峒肉羹、郭記大塊肉羹、紅茶屋' 
  // },
  // { 
  //   name: '延三夜市', 
  //   lat: 25.0664, 
  //   lng: 121.5126, 
  //   hours: '每日 18:00 - 00:00', 
  //   note: '施家鮮肉湯圓、大橋頭老牌筒仔米糕、高麗菜飯' 
  // },
  { name: '台中逢甲夜市', lat: 24.1802, lng: 120.6450, hours: '每日 16:00 - 02:00', note: '明倫蛋餅、日船章魚小丸子' },
  { name: '花蓮東大門夜市', lat: 23.9745, lng: 121.6115, hours: '每日 17:00 - 00:00', note: '第一家烤肉、林記燒番麥' },
  { name: '高雄瑞豐夜市', lat: 22.6661, lng: 120.2998, hours: '週二、四、五、六、日 17:00 - 01:00', note: '🛑 週一、三公休！<br>天使雞排、萬國牛排' }
];

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
    wheelPxPerZoomLevel: 120
  });

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

  // 4. 加入夜市 Marker
  tasks.forEach(task => {
    const marker = L.circleMarker([task.lat, task.lng], {
      radius: 8,
      fillColor: '#ff4757',
      color: '#fff',
      weight: 2,
      fillOpacity: 1,
      zIndexOffset: 1000
    }).addTo(map.value);

    marker.on('click', function(e) {
      L.DomEvent.stopPropagation(e); 
      map.value.flyTo([task.lat, task.lng], 9, {
        animate: true,
        duration: 1.2,
        noMoveStart: true
      });

      L.popup({
        autoPan: false,
        offset: [0, -10],
        closeButton: false,
        className: 'custom-popup'
      })
        .setLatLng([task.lat, task.lng])
        .setContent(`
          <div style="text-align: center; min-width: 150px;">
            <h3 style="margin: 0 0 5px 0; color: #d63031;">${task.name}</h3>
            <div style="font-size: 13px; color: #333; margin-bottom: 5px;">
              <strong>🕒 ${task.hours}</strong>
            </div>
            <div style="font-size: 12px; color: #636e72;">
              ${task.note}
            </div>
          </div>
        `)
        .openOn(map.value);
    });
  });
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

</template>



<style scoped>

.map-weather-left-frame {
    width: 35%;
    height: 100%;
    border: 1px solid red;
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