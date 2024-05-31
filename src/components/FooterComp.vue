<template>
    <div class="col-12" id="footer-container">
        <div class="col-6">
            <div style="display: flex; flex: 1; flex-direction: row; justify-content: center; align-items: center;">
                <h1>Camspot</h1>
                <img style="width: 45px; height: 45px; padding-bottom: 7px; padding-left: 5px;"
                    src="https://cdn-icons-png.flaticon.com/512/8742/8742260.png" />
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
            <div class="social-item"><i class="fab fa-instagram"></i> <span>Instagram</span></div>
            <div class="social-item"><i class="fab fa-github"></i> <span>Github</span></div>
            <div class="social-item"><i class="fas fa-envelope"></i> <span>Email</span></div>
            <div class="social-item"><i class="fas fa-phone"></i> <span>Phone number</span></div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FooterComp',
    data() {
        return {
            username: ''
        }
    },
}
</script>

<style>
#footer-container {
    background-color: #563D7C;
    color: white;
    height: 100%;
    display: flex;
    flex: 1;
    flex-direction: row;
    flex-wrap: wrap;
    /* margin-right: 1000px; */
}

#footer-container>div {
    margin-top: 20px;
    /* margin-right: 200px; */
    display: flex;
    flex: 1;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

h1 {
    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
}

h3 {
    font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
    font-weight: 500;
}
.social-item {
    display: flex;
    align-items: center;
    margin-bottom: 20px; /* Add this line */
}
.social-item > span {
    margin-left: 10px;
}

@media screen and (max-width: 700px) {
    h1 {
        font-size: 1.8em;
        margin-left: 10px;
    }

    h3 {
        font-size: 1.2em;
    }

    #footer-container {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-gap: 20px;
    }

    #footer-container>div {
        padding-top: 10px;
        width: 100%;
        min-width: 100%;
        margin-top: 0px;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }
}
</style>