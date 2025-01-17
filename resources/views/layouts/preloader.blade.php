<div id="preloader2" class="preloader2">
    <div class="loader11">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>

<script>
    window.addEventListener('load', function() {
        // Delay hiding the preloader for an additional 0.7 seconds after the page loads
        setTimeout(function() {
            document.getElementById('preloader2').style.display = 'none';
        }, 700); // 700 milliseconds = 0.7 seconds
    });
</script>


<style>
    .preloader2 {
        position: fixed;
        /* Cover the whole screen */
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.8);
        /* Light white background */
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        /* Make sure it is above all content */
    }

    .loader11 {
        width: 100px;
        height: 70px;
        position: relative;
    }

    .loader11 span {
        display: block;
        width: 5px;
        height: 10px;
        background: #e43632;
        position: absolute;
        bottom: 0;
        animation: loading-11 2.25s infinite ease-in-out;
    }

    .loader11 span:nth-child(2) {
        left: 11px;
        animation-delay: .2s;
    }

    .loader11 span:nth-child(3) {
        left: 22px;
        animation-delay: .4s;
    }

    .loader11 span:nth-child(4) {
        left: 33px;
        animation-delay: .6s;
    }

    .loader11 span:nth-child(5) {
        left: 44px;
        animation-delay: .8s;
    }

    .loader11 span:nth-child(6) {
        left: 55px;
        animation-delay: 1s;
    }

    .loader11 span:nth-child(7) {
        left: 66px;
        animation-delay: 1.2s;
    }

    .loader11 span:nth-child(8) {
        left: 77px;
        animation-delay: 1.4s;
    }

    .loader11 span:nth-child(9) {
        left: 88px;
        animation-delay: 1.6s;
    }

    @keyframes loading-11 {
        0% {
            height: 10px;
            transform: translateY(0);
            background: #ff4d80;
        }

        25% {
            height: 60px;
            transform: translateY(15px);
            background: #3423a6;
        }

        50% {
            height: 10px;
            transform: translateY(-10px);
            background: #e29013;
        }

        100% {
            height: 10px;
            transform: translateY(0);
            background: #e50926;
        }
    }
</style>
