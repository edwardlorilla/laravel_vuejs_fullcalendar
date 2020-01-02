<template>
    <div class="modal fade" :class="show ? 'show' : ''"  tabindex="-1" role="dialog" :style="show ? 'display: block; padding-right: 17px;' : ''" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Modal title</h5>
                    <button type="button" @click="$emit('close')" class="close"  aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="text" v-model="title">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="$emit('close')">Close</button>
                    <button v-if="info.id" type="button" class="btn btn-danger" @click="onDelete">Delete</button>
                    <button  type="button" class="btn btn-primary" @click="save(title)">{{info.id ? 'Update' : 'Save'}} changes</button>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:{
            show: Boolean,
            save: Function,
            info: Object
        },
        data(){
            return{
                title: ''
            }
        },
        mounted(){
            this.title = this.info.id ?  this.info.title : ''
        },
        methods:{
            onDelete(){
                let res = confirm('Are you sure you want to remove event')
                if(res){
                    this.$emit('delete', this.  info)
                }
            }

        },
        name: "Modal"
    }
</script>

<style scoped>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }
    .modal-enter {
        opacity: 0;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }
</style>