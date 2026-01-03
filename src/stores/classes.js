import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useclassesStore = defineStore('classes', () => {
  const pageToTurn = ref(1)
  const currentPage = ref(0)
  const imgUrl = ref(null)
  const setPage = (num)=>{
    pageToTurn.value = num;
    currentPage.value = num;
  }
  const imgShare = (data)=>{
    imgUrl.value = data
  }
  const changeIndex = (num)=>{
    currentPage.value = num;
  }
  return { pageToTurn , setPage, imgShare,imgUrl,currentPage,changeIndex}
})
