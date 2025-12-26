import { ref } from 'vue'
import { defineStore } from 'pinia'

export const useclassesStore = defineStore('classes', () => {
  const pageToTurn = ref(1)
  const setPage = (num)=>{
    pageToTurn.value = num;
  }
  return { pageToTurn , setPage }
})
