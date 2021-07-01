<template>
    <div>
        <div class="card">
            <div class="card-header">My Profile</div>

            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-sm-3 col-md-2 col-5">
                        <label style="font-weight:bold;">Name</label>
                    </div>
                    <div class="col-md-8 col-6">
                        {{ user.name }}
                    </div>
                </div>
                <hr />

                <div class="row justify-content-center">
                    <div class="col-sm-3 col-md-2 col-5">
                        <label style="font-weight:bold;">Email</label>
                    </div>
                    <div class="col-md-8 col-6">
                        {{ user.email }}
                    </div>
                </div>
                <hr />
                <div
                    v-if="user.date_of_birth"
                    class="row justify-content-center"
                >
                    <div class="col-sm-3 col-md-2 col-5">
                        <label style="font-weight:bold;">Date of Birth</label>
                    </div>
                    <div class="col-md-8 col-6">
                        {{ user.date_of_birth | myDate }}
                    </div>
                </div>
                <hr v-if="user.date_of_birth" />
                <div v-if="user.gender" class="row justify-content-center">
                    <div class="col-sm-3 col-md-2 col-5">
                        <label style="font-weight:bold;">Gender</label>
                    </div>
                    <div class="col-md-8 col-6">
                        {{ user.gender | upText }}
                    </div>
                </div>
                <hr v-if="user.gender" />
                <div class="row justify-content-center">
                    <div class="col-sm-3 col-md-2 col-5">
                        <label style="font-weight:bold;">Member Since</label>
                    </div>
                    <div class="col-md-8 col-6">
                        {{ user.created | myDate }}
                    </div>
                </div>
                <hr />
                <div class="row justify-content-center">
                    <button
                        @click="editModal(user)"
                        class="btn btn-warning mb-2"
                    >
                        Edit
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Form -->
        <div
            class="modal fade"
            id="edituser"
            tabindex="-1"
            role="dialog"
            aria-labelledby="edituserLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="edituserLabel">
                            Update Profile
                        </h5>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="updateUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <input
                                    v-model="form.name"
                                    type="text"
                                    name="name"
                                    placeholder="Name"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('name')
                                    }"
                                />

                                <HasError :form="form" field="name" />
                            </div>

                            <div class="form-group">
                                <input
                                    v-model="form.email"
                                    type="email"
                                    name="email"
                                    placeholder="Email Address"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('email')
                                    }"
                                />
                                <HasError :form="form" field="email" />
                            </div>
                            <div class="form-group">
                                <select
                                    name="gender"
                                    v-model="form.gender"
                                    id="gender"
                                    class="form-control"
                                    :class="{
                                        'is-invalid': form.errors.has('gender')
                                    }"
                                >
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="none">None</option>
                                </select>
                                <has-error
                                    :form="form"
                                    field="gender"
                                ></has-error>
                            </div>
                            <div class="form-group">
                                <div>
                                    <label>Date of Birth:</label>

                                    <input
                                        v-model="form.date_of_birth"
                                        type="date"
                                        name="date_of_birth"
                                        placeholder=""
                                        class="form-control"
                                        :class="{
                                            'is-invalid': form.errors.has(
                                                'date_of_birth'
                                            )
                                        }"
                                    />
                                </div>
                                <has-error
                                    :form="form"
                                    field="date_of_birth"
                                ></has-error>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button
                                @click="cancelModal()"
                                type="button"
                                class="btn btn-danger"
                                data-dismiss="modal"
                            >
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-success">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Form Ends -->
    </div>
</template>
<script type="text/javascript"></script>
<script>
export default {
    data() {
        return {
            user: {
                name: "",
                email: "",
                created: "",
                gender: "",
                date_of_birth: ""
            },
            form: new Form({
                id: "",
                name: "",
                email: "",
                created: "",
                gender: "",
                date_of_birth: ""
            })
        };
    },
    created() {
        this.fetchDetails();

        Fire.$on("LoadEditedProfile", () => {
            //custom events fire on
            this.fetchDetails();
            this.form.reset();
        });
    },
    methods: {
        fetchDetails() {
            axios.get("/api/auth/show").then(response => {
                this.user.name = response.data.name;
                this.user.email = response.data.email;
                this.user.gender = response.data.gender;
                this.user.date_of_birth = response.data.date_of_birth;
                this.user.created = response.data.created;
            });
        },
        updateUser() {
            // console.log('Editing data');
            this.form
                .put("api/auth/update")
                .then(response => console.log(response.data.message))
                .then(() => {
                    ///
                    Toast.fire({
                        icon: "success",
                        title: "User updated successfully"
                    });

                    Fire.$emit("LoadEditedProfile");

                    $("#edituser").modal("hide");
                })

                .catch(error => {
                    console.log("Error:: ", error.response.data);
                });
        },
        editModal(user) {
            //this.editmode = true;
            this.form.reset();
            $("#edituser").modal("show");

            this.form.fill(user);
        },
        cancelModal() {
            this.form.reset();
            $("#edituser").modal("hide");
        }
    }
};
</script>
