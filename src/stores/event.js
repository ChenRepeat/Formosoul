import { defineStore } from 'pinia';
import { ref, computed } from 'vue';

export const useEventData = defineStore('eventData', () =>{
  const splitTextToRawArray = (text) => {
    if (!text) return [];
    const rawWords = text.split(" "); 
    const resultArray = [];
    for (let i = 0; i < rawWords.length; i++) {
      const word = rawWords[i];
      if (word !== "") {
        resultArray.push(word);
      }
    }
    return resultArray;
  };
  const eventDatas = ref([]);
  eventDatas.value = [{}];
  const loadeventData = async () => {
    const apiBase = import.meta.env.VITE_API_BASE;
    const API_URL = `${apiBase}/getAnnualEventInfo.php`;
    try{
      const response = await fetch(API_URL, {
        method: 'POST',
        headers:{
          'Content-Type': 'application/json; charset=utf-8'
        },
        credentials: 'include',
        body: JSON.stringify({})
      });
      const result = await response.json();
      if(result.success){
        const dbData = result.data;
         eventDatas.value = dbData.map(item => ({
          id: item.annalevent_ID,
          title_en: item.title_en,
          title_en_s: splitTextToRawArray(item.title_en),
          title_zh: item.title_zh,
          intro_en: item.content_summary_en,
          intro_zh: item.content_summary_zh,
          introL_en: item.content_en,
          introL_zh: item.content_zh,
          launchDate: item.launchdate,
          pic: item.pic,
          status: item.status,
          video: item.video,
        }));
        console.log(eventDatas.value);
        
      }else{
        console.error(result.message);
      }
    }catch(error){
      console.error("Fetch 發生錯誤:", error);
    }
  };

   
  return{
    loadeventData,eventDatas
  };
});