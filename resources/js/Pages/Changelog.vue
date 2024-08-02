<script setup>
// import * as showdown from '../showdown.js';
// let showdown = require('../showdown');

import { onMounted } from 'vue';

const props = defineProps(['content']);

onMounted(() => {
    const script = document.createElement('script');
    script.src = '/js/showdown.js'; // Path to your external script
    script.async = true;
    document.body.appendChild(script);

    script.onload = () => {
        var converter = new showdown.Converter();
        converter.setFlavor('github');
        var text = document.getElementById("input").innerHTML;
        var html = converter.makeHtml(text);

        document.getElementById("input").remove();
        document.getElementById("output").innerHTML = html;

        // const style = document.createElement('style');
        // style.textContent = `
        // #aboutlaravel {
        //     color: #f00;
        // }
        // `;
        // document.head.appendChild(style);

        // const link = document.createElement('link');
        // link.rel = 'stylesheet';
        // link.href = '/css/external-style.css'; // Path to your external CSS file
        // document.head.appendChild(link);
    }
});
</script>

<template>
    <div id="input">{{ props.content }}</div>
    <div id="output"></div>
</template>