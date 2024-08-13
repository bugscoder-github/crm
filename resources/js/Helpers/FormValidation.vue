<script>
export default {
    methods: {
        getError(type) {
            return this.errors[type] !== undefined && this.errors[type].length ? this.errors[type][0] : '';
        }
    }
}
</script>