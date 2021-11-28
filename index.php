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

<body>
    <div class="container" id="crudApp">
        <br />
        <h3 align="center">Insert & Read to-from MS Access using MYSQL ODBC Driver</h3>
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="panel-title">Doctors List</h3>
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
                            <th>Full Name</th>
                            <th>Phone Number</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <tr v-for="row in doctor">
                            <td>{{ row.name }}</td>
                            <td>{{ row.phone }}</td>
                            <td><button type="button" name="edit" class="btn btn-primary btn-xs edit"
                                    @click="fetchData(row.id)">Edit</button></td>
                            <td><button type="button" name="delete" class="btn btn-danger btn-xs delete"
                                    @click="deleteData(row.id)">Delete</button></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <br>
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="panel-title">Patients List</h3>
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
                            <th>Full Name</th>
                            <th>Phone Number</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        <tr v-for="row in patient">
                            <td>{{ row.name }}</td>
                            <td>{{ row.phone }}</td>
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
                <div class="modal-mask">
                    <div class="modal-wrapper">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" @click="myModel=false"><span
                                            aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title">{{ dynamicTitle }}</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label>Enter Name</label>
                                        <input type="text" class="form-control" v-model="name" />
                                    </div>
                                    <div class="form-group">
                                        <label>Enter Phone</label>
                                        <input type="text" class="form-control" v-model="phone" />
                                    </div>
                                    <div class="form-group">
                                        <label>User Role</label>
                                        <select class="form-select" v-model="role" aria-label="Default select example">
                                            <option selected>Select User Role</option>
                                            <option value="1">Doctor</option>
                                            <option value="2">Patient</option>
                                        </select>
                                        <!-- <input type="text" class="form-control" v-model="phone" /> -->
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
                    for(var i = 0; i <= this.application.allData.length; i++) {
                        this.application.doctor = this.application.allData.filter(i=>i.role == 1)
                    }
                    for(var i = 0; i <= this.application.allData.length; i++) {
                        this.application.patient = this.application.allData.filter(i=>i.role == 2)
                    }
                    console.log(this.doctor)
                    console.log(this.patient)
                });
            },
            openModel: function () {
                application.name = '';
                application.phone = '';
                application.actionButton = "Insert";
                application.dynamicTitle = "Add Data";
                application.myModel = true;
            },
            submitData: function () {
                if (application.name != '' && application.phone != '' && application.role != '') {
                    if (application.actionButton == 'Insert') {
                        axios.post('action.php', {
                            action: 'insert',
                            name: application.name,
                            phone: application.phone,
                            role: application.role
                        }).then(function (response) {
                            application.myModel = false;
                            application.fetchAllData();
                            application.name = '';
                            application.phone = '';
                            application.role = '';
                            alert(response.data.message);
                        });
                    }
                    if (application.actionButton == 'Update') {
                        axios.post('action.php', {
                            action: 'update',
                            name: application.name,
                            phone: application.phone,
                            role: application.phone,
                            hiddenId: application.hiddenId
                        }).then(function (response) {
                            application.myModel = false;
                            application.fetchAllData();
                            application.name = '';
                            application.phone = '';
                            application.role = '';
                            application.hiddenId = '';
                            alert(response.data.message);
                        });
                    }
                }
                else {
                    alert("Fill All Field");
                }
            },
            fetchData: function (id) {
                axios.post('action.php', {
                    action: 'fetchSingle',
                    id: id
                }).then(function (response) {
                    application.name = response.data.name;
                    application.phone = response.data.phone;
                    application.hiddenId = response.data.id;
                    application.myModel = true;
                    application.actionButton = 'Update';
                    application.dynamicTitle = 'Edit Data';
                });
            },
            deleteData: function (id) {
                if (confirm("Are you sure you want to remove this data?")) {
                    axios.post('action.php', {
                        action: 'delete',
                        id: id
                    }).then(function (response) {
                        application.fetchAllData();
                        alert(response.data.message);
                    });
                }
            }
        },
        created: function () {
            this.fetchAllData();
        }
    });

</script>