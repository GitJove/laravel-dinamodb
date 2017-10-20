<template>
    <div class="container">
        <div class="row">
            <div v-on:click="toni()" class="col-md-8 col-md-offset-2">
                <div @click="toni()"class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        I'm an example component!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
      mounted() {
        console.log('Component mounted.')
      },
      method: {
        toni() {

        }
      }
    }
</script>
