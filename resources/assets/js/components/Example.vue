<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                {{message}}
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return {
                message: "hello"
            };

        },
        mounted(){
            console.log("I am ready");
            this.$http.post('/python-data')
            .then(response => {
                console.log(response.data)
            });
        }
    }
</script>
