import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useclassesStore = defineStore('classes', () => {
  const pageToTurn = ref(1)
  const setPage = (num)=>{
    pageToTurn.value = num;
  }
  const imgUrl = ref(null)
  const imgShare = (data)=>{
    imgUrl.value = data
  }
  return { pageToTurn , setPage, imgShare,imgUrl}
})
