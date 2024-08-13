<template>
    <div class="modal fade" id="item-template-modal" aria-hidden="true" aria-labelledby="modal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $t('Add Item') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <table id="template-tables" class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ $t('Name') }}</th>
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

        window.itemTemplateModal = this;
    },
    methods: {
        initDataTables() {
            let t = this;

            t.table = new DataTable('#template-tables', {
                processing: true,
                serverSide: true,
                ajax: route('template.services.datatables'),
                pagingType: 'simple',
                paging: false,
                order: [[1, 'desc']],
                stateSave: true,
                columnDefs: [
                    {
                        render: function (data, type, full, meta) {
                            return `
                                <a href="#" class="btn btn-primary" onclick="event.preventDefault(); window.itemTemplateModal.addItem(${data})">${ t.$t('Select') }</a>
                            `;
                        },
                        targets: 1
                    }
                ],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'id', name: 'id' },
                ]
            });
        },

        open() {
            this.modal = new bootstrap.Modal(document.getElementById('item-template-modal'), {
                keyboard: false
            });
            this.modal.toggle();
        },

        addItem(templateId) {
            let t = this;
            return axios.get(route('template.services.retrieve', { id: templateId })).then((response) => {
                    let data = response.data;

                    let arrayTemplate = [];

                    for (var i = 0; i < data.length; i++) {
                        let template = {...this.itemTemplate};
                        template.item_type = 'service';
                        template.service_id = data[i];

                        arrayTemplate.push(template)
                    }

                    t.$emit('modalTemplateAddItem', arrayTemplate);
                    t.modal.toggle();
                }).catch((e) => {
                    // Do Error
                });
        }
    }
}
</script>