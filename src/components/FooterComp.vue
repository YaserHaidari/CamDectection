<template>
    <div class="col-12" id="footer-container">
        <div class="col-6">
            <div style="display: flex; flex: 1; flex-direction: row; justify-content: center; align-items: center;">
                <h1>Camspot</h1>
                <img style="width: 45px; height: 45px; padding-bottom: 7px; padding-left: 5px;" src="https://cdn-icons-png.flaticon.com/512/8742/8742260.png"/>
            </div>
            <p><i>Made by Yaser Haidari, 
                Swinburne Univeristy of Technology</i></p>
            <p>&COPY; 2024 All rights reserved</p>
        </div>
        <div class="col-2" style="align-items: flex-start !important;">
            <h3>Help & support</h3>
            <p>FAQ</p>
            <p>Contact us</p>
            <p>Privacy policy</p>
            <p>Terms & Condition</p>
        </div>
        <div class="col-2" style="align-items: flex-start !important;">
            <h3>About us</h3>
            <p>Team</p>
            <p>Background</p>
            <p>Join team</p>
            <p>Apply</p>
        </div>
        <div class="col-2" style="align-items: flex-start !important;">
            <h3>Socials</h3>
            <p>Instagram</p>
            <p>Github</p>
            <p>Email</p>
            <p>Phone number</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FooterComp',
    data(){
        return{
            username: ''
        }
    },
}
</script>

<style>
#footer-container{
    background-color: #563D7C;
    color: white;
    height: 100%;
    display: flex;
    flex: 1;
    flex-direction: row;
}
#footer-container > div{
   margin-top: 20px;
   display: flex;
   flex: 1;
   flex-direction: column;
   justify-content: center;
   align-items: center;
}
h1{
    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
}
h3{
    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
    font-weight: 500;
}
</style>