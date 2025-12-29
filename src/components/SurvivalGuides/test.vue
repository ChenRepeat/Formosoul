<script setup>
  // 從local storge裡面找user，通常是member_id
  const get_order = () => {
        const storedUser = localStorage.getItem('user'); 
        const apiBase = import.meta.env.VITE_API_BASE;
        // 以上是環境變數的設定
        const API_URL = `${apiBase}/getmemberorder.php`; // /getmemberorder.php改成自己的php的名稱
        if(!storedUser) return;
        const userData = JSON.parse(storedUser);
        const { member_ID } = userData; // 解構賦值


        return fetch(API_URL, {
            method: 'POST', // 通常都用POST
            headers: { // 這邊是固定的
                'Content-Type' : 'application/json'
            },
            body: JSON.stringify({
                member_ID
            }) // 讓member_id字串化
        }
        ).then( res => res.json() // 固定這樣寫，res 回傳json，這時候還沒拿到資料！
        ).then( order_response => { // order_response 可以自己取 可以叫做response
            // 真正的陣列在 order_response.data 裡面
            const realArray = order_response.data || [];  // .data 是要跟前端取的一樣的名字
            // localStorage.setItem('data', JSON.stringify(realArray));
            orders.value = realArray;
        });
    };

</script>