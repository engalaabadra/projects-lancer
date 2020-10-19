<template>
<div>
    <li class="list-group-item" :class="className">
        <slot></slot>
    </li>
    <small class="badge float-right badge-danger">{{user}}</small>
    <span id="time">{{time}}</span>

</div>
</template>

<script>
export default {

    props: [
        'color',
        'user',
        'time'
    ],
    computed: {
        className() {
            return 'list-group-item-' + this.color
        }
    },
    data() {
        return {
            message: ''

        }

    },
    methods: {

    }
}
</script>

<style>
#time {
    font-size: 10px;
    margin-top: 10px;
    color: black;
}
</style>
