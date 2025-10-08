require.config({
    paths: {
        'datatables.net': 'https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min',
        'datatables.net-bs4': 'https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min',
        'datatables.net-buttons': 'https://cdn.datatables.net/buttons/2.3.3/js/dataTables.buttons.min',
        'datatables.net-buttons-bs4': 'https://cdn.datatables.net/buttons/2.3.3/js/buttons.bootstrap4.min',
        'datatables.net-buttons-colvis': 'https://cdn.datatables.net/buttons/2.3.3/js/buttons.colVis.min',
        'datatables.net-buttons-print': 'https://cdn.datatables.net/buttons/2.3.3/js/buttons.print.min',
        'datatables.net-buttons-html': 'https://cdn.datatables.net/buttons/2.3.3/js/buttons.html5.min',
        'datatables.net-responsive': 'https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min',
        'datatables.net-responsive-bs4': 'https://cdn.datatables.net/responsive/2.4.0/js/responsive.bootstrap4.min',
        'datatables.net-scroller-bs4': 'https://cdn.datatables.net/scroller/2.0.0/js/dataTables.scroller.min',
        'datatables.net-select-bs4': 'https://cdn.datatables.net/select/1.3.3/js/dataTables.select.min'
    }
});

define([
    'jquery',
    'core/str',
    'core/ajax',
    'core/notification',
    'datatables.net',
    'datatables.net-bs4',
    'datatables.net-buttons',
    'datatables.net-buttons-bs4',
    'datatables.net-buttons-colvis',
    'datatables.net-buttons-print',
    'datatables.net-buttons-html',
    'datatables.net-responsive',
    'datatables.net-responsive-bs4',
    'datatables.net-scroller-bs4',
    'datatables.net-select-bs4'
], function($, str, ajax, notification) {

    var userlist = {
        dom: {
            main: null,
            table: null
        },
        langs: {
            somethingWentWrong: null,
            userlist: null
        },
        variables: {
            dataTableReference: null,
            baseurl: null
        },

        actions: {
            getString: function() {

                str.get_strings([
                    { key: 'something_went_wrong', component: 'local_reporttab' },
                    { key: 'user_reports_title', component: 'local_reporttab' }
                ]).done(function(s) {
                    userlist.langs.somethingWentWrong = s[0];
                    userlist.langs.userlist = s[1];
                    userlist.init();
                });
            },

            getuserlist: function(selectedBatch) {
                var promises = ajax.call([{
                    methodname: 'local_assessmentreport_get_user_reports',
                    args: {
                            batch : selectedBatch,
                    }
                }]);

                promises[0].done(function(response) {
                    if (userlist.variables.dataTableReference) {
                        userlist.variables.dataTableReference.clear();
                        userlist.variables.dataTableReference.rows.add(response.data);
                        userlist.variables.dataTableReference.draw();
                    }
                }).fail(function() {
                    notification.addNotification({
                        type: 'error',
                        message: userlist.langs.somethingWentWrong
                    });
                });
            }
        },

        init: function() {
            userlist.dom.main = $('#user_reports');
            userlist.dom.table = userlist.dom.main.find('#userreportTable');

            userlist.variables.dataTableReference = userlist.dom.table.DataTable({
                responsive: true,
                scrollCollapse: true,
                serverSide: false,
                processing: true,
                order: [[0, "asc"]],
                
                // ✅ Buttons with exportOptions to include hidden columns
                buttons: [
                    { 
                        extend: 'copy', 
                        className: 'btn btn-sm btn-purple text-white me-2', 
                        text: 'Copy',
                        exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17] } 
                    },
                    { 
                        extend: 'csv', 
                        className: 'btn btn-sm btn-purple text-white me-2', 
                        text: 'CSV',
                        exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17] } 
                    },
                    { 
                        extend: 'excel', 
                        className: 'btn btn-sm btn-purple text-white me-2', 
                        text: 'Excel',
                        exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17] } 
                    },
                    { 
                        extend: 'print', 
                        className: 'btn btn-sm btn-purple text-white', 
                        text: 'Print',
                        exportOptions: { columns: [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17] } 
                    }
                ],


                // ✅ Column definitions (hidden columns marked with visible: false)
                columns: [
                    { data: 'batch' },          
                    { data: 'username' },
                    { data: 'email', visible: false },         
                    { data: 'phone', visible: false },       
                    { data: 'degree', visible: false },
                    { data: 'collegename', visible: false },
                    { data: 'gender', visible: false },
                    { data: 'department' },
                    { data: 'cgpa', visible: false },  
                    { data: 'questiontype' },
                    { data: 'correct25' },       
                    { data: 'correct2665' },     
                    { data: 'total_correct' },   
                    { data: 'work_on_chennai', visible: false }, 
                    { data: 'backlog', visible: false },         
                    { data: 'offerinhand', visible: false },     
                    { data: 'immediatejoin', visible: false },
                    { data: 'timecreated' }
                ],

                // ✅ Layout
                dom: 
                    "<'row mb-3'<'col-md-12 d-flex justify-content-end align-items-center'Bf>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-3'<'col-sm-12 col-md-5 dt-info'i><'col-sm-12 col-md-7 d-flex justify-content-end'p>>",

                initComplete: function() {
                    $('.dt-buttons').addClass('pr-2 d-flex align-items-center');

                    // Clean up the search box label text
                    $('.dataTables_filter label').each(function () {
                        const $label = $(this);
                        const $input = $label.find('input');
                        $input.attr('placeholder', 'Search');
                        $label.contents().filter(function () { return this.nodeType === 3; }).remove();
                    });

                    // ✅ Create batch dropdown with "All Batch" as first option
                    var $batchSelector = $('<select id="batchSelector" class="form-select mb-2 mr-2" style="border-radius:12px; padding:8px 12px; width:auto; min-width:150px;"></select>');
                    $batchSelector.append('<option value="0">All Batch</option>'); // empty value = show all
                    for (var i = 1; i <= 10; i++) {
                        $batchSelector.append('<option value="' + i + '">Batch ' + i + '</option>');
                    }

                    // Append the dropdown after the DataTables buttons
                    $('.dt-buttons').after($batchSelector);

                    // Filter table on batch selection
                    $('#batchSelector').on('change', function() {
                        var selectedBatch = $(this).val();
                        userlist.actions.getuserlist(selectedBatch);
                    });
                },

                language: {
                    emptyTable: "No data available",
                    paginate: {
                        previous: "<",
                        next: ">"
                    }
                }
            });

            var selectedBatch = 0;
            userlist.actions.getuserlist(selectedBatch);
        }

    };

    return {
        init: userlist.actions.getString
    };
    
});

