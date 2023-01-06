<template>
    <div :class="estilo" role="alert">
        {{titulo}}
        <hr>
        <p>{{detalhes.mensagem}}</p>
        <br>
        <ul style="list-style-type:none" v-if="detalhes.dados">
            <li v-for="e, key in detalhes.dados" :key="key">{{ e[0] }}</li>
        </ul>
    </div> 
</template>

<script>
    export default {
       props:['tipo','detalhes','titulo'],
       computed:{
        estilo(){
            return 'alert alert-' + this.tipo;
        }
       }
    }
</script>
