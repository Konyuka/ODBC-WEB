<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ACCESS CRUD</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <style>
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

        .modal-wrapper {
            display: table-cell;
            vertical-align: middle;
        }
    </style>
</head>

<body class="overflow-auto">
    <div class="container overflow-auto" id="crudApp">
        <br />
        <h3 align="center">READ FROM MYSQL DB USING PHP & JS</h3>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="panel-title">Employees List</h3>
                    </div>
                    <div class="col-md-6" align="right">
                        <input type="button" class="btn btn-success btn-xs" @click="openModel" value="Add" />
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Position</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <tr v-for="row in allData">
                            <td>{{ row.first }}</td>
                            <td>{{ row.last }}</td>
                            <td>{{ row.position }}</td>
                            <td><button type="button" name="edit" class="btn btn-primary btn-xs edit"
                                    @click="fetchData(row.id)">Edit</button></td>
                            <td><button type="button" name="delete" class="btn btn-danger btn-xs delete"
                                    @click="deleteData(row.id)">Delete</button></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div v-if="myModel">
            <transition name="model">
                <div class="modal-mask overflow-auto">
                    <div class="modal-wrapper overflow-auto">
                        <div class="modal-dialog overflow-auto">
                            <div class="modal-content overflow-auto">
                                <div class="modal-header">
                                    <button type="button" class="close" @click="myModel=false"><span
                                            aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">{{ dynamicTitle }}</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="first" placeholder="first"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="last"  placeholder="last"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="address" placeholder="address"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="position" placeholder=" position"/>
                                    </div>

                                    
                                    <br />
                                    <div align="center">
                                        <input type="hidden" v-model="hiddenId" />
                                        <input type="button" class="btn btn-success btn-xs" v-model="actionButton"
                                            @click="submitData" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</body>

</html>

<script>

    var application = new Vue({
        el: '#crudApp',
        data: {
            allData: '',
            doctor:'',
            patient:'',
            myModel: false,
            actionButton: 'Insert',
            dynamicTitle: 'Add Data',
        },
        methods: {
            fetchAllData: function () {
                axios.post('action.php', {
                    action: 'fetchall'
                }).then(function (response) {
                    console.log(response.data)
                    application.allData = response.data;
                });
            },
            openModel: function () {
                application.first = '';
                application.last = '';
                application.address = '';
                application.position = '';
                application.actionButton = "Insert";
                application.dynamicTitle = "Add Data";
                application.myModel = true;
            },
            submitData: function () {
                if (application.regno != '') {
                    if (application.actionButton == 'Insert') {
                        axios.post('action.php', {
                            action: 'insert',
                            position: application.position,
                            address: application.address,
                            last: application.last,
                            first: application.first,
                        }).then(function (response) {
                            application.myModel = false;
                            application.fetchAllData();
                            application.first = '';
                            application.last = '';
                            application.address = '';
                            application.position = '';
                            alert(response.data.message);
                        });
                    }}
            },
        },
        created: function () {
            this.fetchAllData();
        }
    });

</script>