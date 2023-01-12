<template>
    <div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col" v-for="t,key in titulos" :key="key" class="text-uppercase">{{t.titulo}}</th>
                    <th v-if="visualizar.visivel || atualizar || remover"></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="obj, chave in dadosFiltrados" :key="chave">
                    <td v-for="valor, chaveValor in obj" :key="chaveValor">
                        <span v-if="titulos[chaveValor].tipo == 'texto'">{{valor}}</span>
                        <span v-if="titulos[chaveValor].tipo == 'data'">{{valor | formatDate}}</span>
                        <span v-if="titulos[chaveValor].tipo == 'imagem'">
                            <img src="'/storage/'+valor" width="30" height="30">
                        </span>
                    </td>
                    <td>
                        <button v-if="visualizar.visivel" class="btn btn-outline-primary btn-sm" :data-toggle="visualizar.dataToggle" :data-target="visualizar.dataTarget" @click="setStore(obj)">Visualizar</button>
                        <button v-if="atualizar" class="btn btn-outline-primary btn-sm">Atualizar</button>
                        <button v-if="remover" class="btn btn-outline-danger btn-sm">Remover</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        props:['dados', 'titulos', 'visualizar', 'atualizar', 'remover'],
        methods:{
            setStore(obj){
                this.$store.state.item = obj;
            }
        },
        computed: {
            dadosFiltrados() {
                let campos = Object.keys(this.titulos)
                let dadosFiltrados = []

                this.dados.map((item, chave) => {
                    let itemFiltrado = {}
                    campos.forEach(campo => {
                        itemFiltrado[campo] = item[campo] 
                    })
                    dadosFiltrados.push(itemFiltrado)
                })
                return dadosFiltrados
            }
        }
    }
</script>
