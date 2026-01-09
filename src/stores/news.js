import { defineStore } from 'pinia';
import { ref } from 'vue';


export const useNewsData = 
defineStore('newsData', () => { const baseUrl = import.meta.env.VITE_API_BASE
  
// 處理日期格式
const formatDate = (i) => {
  if(!i) return;
  return i.split(' ')[0];
}

  const allNewsData = ref([]);
  // allNewsData.value = [{}];

  const get_newsinfo = async () => {
  console.log('所有的環境變數：', import.meta.env);
  const storedUser = localStorage.getItem('user');
  const apiUrl = import.meta.env.VITE_API_BASE;
  const NEWS_URL = `${apiUrl}/getNewsInfo.php`;

  // if(!storedUser) {
  //   console.warn('No user data');
  //   return null;
  // }

  const userData = JSON.parse(storedUser);

  try {
    const res = await fetch (NEWS_URL, {
      method: "POST",
      headers: {'Content-Type' : 'application/json'},
      body: JSON.stringify(userData)
    });

    const news_res = await res.json();
    console.log('News:', news_res);
    if(news_res.success && news_res.data?.length) {
      allNewsData.value = news_res.data.map(item => ({
      id: item.newsID,
      title_zh: item.title_zh,
      title_en: item.title_en,
      content_zh: item.content_zh,
      intro_zh: item.intro_zh,
      content_en: item.content_en,
      intro_en: item.intro_en,
      status: item.status,
      createdate: formatDate(item.createdate),
      update: formatDate(item.update),
      pic: item.pic,
      })); 
      console.log(allNewsData.value);

    } else {
      console.warn('No news ', news_res.message);
      return [];
    }
  } catch (error) {
    console.error('Fetch error:', error);
    return null;
  }  
};

  return {
    allNewsData,
    get_newsinfo
  }
})