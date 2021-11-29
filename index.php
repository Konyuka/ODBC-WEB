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
                                        <input type="text" class="form-control" v-model="surname" placeholder="surname"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="middlename"  placeholder=" Middle name"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="firstname" placeholder="First Name "/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="course" placeholder=" course"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="age" placeholder=" age"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="guardian"  placeholder=" guardian"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="phone" placeholder="phone"/>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" v-model="regno" placeholder="regno" />
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
                application.surname = '';
                application.middlename = '';
                application.firstname = '';
                application.course = '';
                application.age = '';
                application.guardian = '';
                application.phone = '';
                application.regno = '';
                application.actionButton = "Insert";
                application.dynamicTitle = "Add Data";
                application.myModel = true;
            },
            submitData: function () {
                if (application.regno != '') {
                    if (application.actionButton == 'Insert') {
                        axios.post('action.php', {
                            action: 'insert',
                            surname: application.surname,
                            middlename: application.middlename,
                            firstname: application.firstname,
                            course: application.course,
                            age: application.age,
                            guardian: application.guardian,
                            phone: application.phone,
                            regno: application.regno
                        }).then(function (response) {
                            application.myModel = false;
                            application.fetchAllData();
                            application.surname = '';
                            application.middlename = '';
                            application.firstname = '';
                            application.course = '';
                            application.age = '';
                            application.guardian = '';
                            application.phone = '';
                            application.regno = '';
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