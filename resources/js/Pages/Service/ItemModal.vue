<template>
    <div class="modal fade" id="item-modal" aria-hidden="true" aria-labelledby="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $t('Add Item') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <table id="tables" class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ $t('Name') }}</th>
                                <th>{{ $t('Price') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="modal-footer py-2">
                    <a class="btn btn-primary float-end" data-dismiss="modal" aria-label="Close">{{ $t('Close') }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import DataTable from 'datatables.net-bs4';
import 'datatables.net-buttons-bs4';
import 'datatables.net-responsive-bs4';

export default {
    props: ['itemTemplate'],

    mixins: [],

    data () {
        return {
            errors: {},
        }
    },
    mounted() {
        this.initDataTables();

        window.itemModalPage = this;
    },
    methods: {
        initDataTables() {
            let t = this;

            t.table = new DataTable('#tables', {
                processing: true,
                serverSide: true,
                ajax: route('service.datatables'),
                pagingType: 'simple',
                paging: false,
                order: [[2, 'desc']],
                stateSave: true,
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return `
                                <a href="#" class="btn btn-primary" onclick="event.preventDefault(); window.itemModalPage.addItem(${data})">${ t.$t('Select') }</a>
                            `;
                        },
                        targets: 2
                    }
                ],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'price', name: 'price' },
                    { data: 'id', name: 'id' },
                ]
            });
        },

        open() {
            this.modal = new bootstrap.Modal(document.getElementById('item-modal'), {
                keyboard: false
            });
            this.modal.toggle();
        },
        addItem(serviceId) {
			let template = {...this.itemTemplate};
            template.item_type = 'service';
            template.service_id = serviceId;

            this.$emit('modalAddItem', template)
            this.modal.toggle();
        }
    }
}
</script>