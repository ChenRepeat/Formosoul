import { defineStore } from "pinia";

export const useMemberStore = defineStore('auth', () => {

    const members = ref([
        {
            name: 'H.Potter',
            wandcore: 'Cornu Cervi Pantotrichum',
            number: 'L09190116',
            date: '2025.09.19',
            isEditing: false, // 控制顯示狀態
            tempName: 'H.Potter' // 暫存編輯中的文字
        }
    ]);

















});