import { defineStore } from "pinia";
import { ref } from "vue";

export const useMemberStore = defineStore('member', () => {
    const publicPath = import.meta.env.BASE_URL;

    // const imgURL = ref(`${publicPath}member/photo.png`);
    const imgURL = ref('');


    const memberData = ref({
        name: 'H.Potter',
        wandcore: 'Cornu Cervi Pantotrichum',
        number: 'L09190116',
        date: '2025.09.19',
        isEditing: false, 
        tempName: 'H.Potter' 
    });


    const updateName = (newName) => {
        memberData.value.name = newName;
    };

    const updatePhoto = (newBase64) => {
        imgURL.value = newBase64;
    };

    return {
        imgURL,
        memberData,
        updateName,
        updatePhoto
    };
});