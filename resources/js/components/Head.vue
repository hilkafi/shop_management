<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Head List</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal()">
                        Add New
                    </button>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>SL.</th>
                      <th>Name</th>
                      <th>Description</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="head in heads.data" :key="head.id">
                      <td>{{ head.id }}</td>
                      <td>{{ head.name }}</td>
                      <td>{{ head.description }}</td>
                      <td>
                          <a href="#" @click="editModal(head)">
                              <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="#" @click="deleteHead(head.id)">
                              <i class="fa fa-trash red"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="heads" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="modal fade" id="add_new_head" tabindex="-1" role="dialog" aria-labelledby="add_new_headLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="add_new_headLabel">Add New User</h5>
                <h5 class="modal-title" v-show="editmode" id="add_new_headLabel">Update User's Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updateHead() : createHead()">
                <div class="modal-body">
                  <div class="form-group">
                    <input v-model="form.name" type="text" name="name" id="name"
                      placeholder="Name"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                    <has-error :form="form" field="name"></has-error>
                  </div>
                  <div class="form-group">
                    <textarea v-model="form.description" name="description" id="description"
                      placeholder="Description"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('description') }"></textarea>
                    <has-error :form="form" field="description"></has-error>
                  </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <button type="submit" v-show="!editmode" class="btn btn-primary">Create</button>
                  <button type="submit" v-show="editmode" class="btn btn-success">Update</button>
                </div>
              </form>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {
      data() {
        return {
          editmode: false,
          heads: {},
          form: new Form({
            id: '',
            name: '',
            description: ''
          })
        }
      },

      methods: {

        getResults(page = 1) {
          axios.get('api/head?page=' + page)
            .then(response => {
              this.heads = response.data;
            });
        },

        updateHead() {
            this.$Progress.finish();
            this.form.put('api/head/' + this.form.id)
            .then( response => {

              this.heads = response.data;
              $('#add_new_head').modal('hide');

              Toast.fire({
                icon: 'success',
                title: 'Head Info Updated successfully'
              });

              this.$Progress.finish();
            })
            .catch( error => {
              this.$Progress.fail();
              console.log(error);
            });
        },

        editModal(head) {
          this.editmode = true;
          this.form.reset();
          $('#add_new_head').modal('show');
          this.form.fill(head);
        },

        newModal() {
          this.editmode = false;
          this.form.reset();
          $('#add_new_head').modal('show');
        },

        loadHead() {
            this.$Progress.start();
            axios.get('api/head').then( response => this.heads = response.data);
            this.$Progress.finish();
        },

        createHead() {
          this.$Progress.start();
          this.form.post('api/head')
          .then(( response ) => {

            $('#add_new_head').modal('hide');
            this.heads = response.data;

            Toast.fire({
              icon: 'success',
              title: 'Head Created successfully'
            });
            this.$Progress.finish();
          })
          .catch( error => console.log(error));
        },

        deleteHead(id) {
          Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
              axios.delete('api/head/' + id)
              .then( response => {
                this.heads = response.data;
                Swal.fire(
                'Deleted!',
                'Your Data has been deleted.',
                'success'
                );
              })
              .catch( error => {
                console.log(error);
                Swal.fire(
                'Failed!',
                'Data Can Not be Deleted.',
                'warning'
                );
              });
            }
          });
        }
      },
      created() {
          this.loadHead();
      }
    }
</script>
