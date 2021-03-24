<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Category List</h3>

                <div class="card-tools">
                    <button class="btn btn-success" @click="newModal()">
                        Add New <i class="fa fa-user-plus"></i>
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
                      <th>Type</th>
                      <th>Description</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="category in categories.data" :key="category.id">
                      <td>{{ category.id }}</td>
                      <td>{{ category.name }}</td>
                      <td>{{ category.type }}</td>
                      <td>{{ category.description }}</td>
                      <td>
                          <a href="#" @click="editModal(category)">
                              <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="#" @click="deleteCategory(category.id)">
                              <i class="fa fa-trash red"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="categories" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="modal fade" id="add_new_category" tabindex="-1" role="dialog" aria-labelledby="add_new_categoryLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="add_new_categoryLabel">Add New User</h5>
                <h5 class="modal-title" v-show="editmode" id="add_new_categoryLabel">Update User's Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updateCategory() : createCategory()">
                <div class="modal-body">
                  <div class="form-group">
                    <input v-model="form.name" type="text" name="name" id="name"
                      placeholder="Name"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                    <has-error :form="form" field="name"></has-error>
                  </div>
                  <div class="form-group">
                    <textarea v-model="form.type" name="type" id="type"
                      placeholder="Type"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('type') }"></textarea>
                    <has-error :form="form" field="type"></has-error>
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
          categories: {},
          form: new Form({
            id: '',
            name: '',
            type: '',
            description: ''
          })
        }
      },

      methods: {

        getResults(page = 1) {
          axios.get('api/category?page=' + page)
            .then(response => {
              this.categories = response.data;
            });
        },

        updateCategory() {
            this.$Progress.finish();
            this.form.put('api/category/' + this.form.id)
            .then( response => {

              this.categories = response.data;
              $('#add_new_category').modal('hide');

              Toast.fire({
                icon: 'success',
                title: 'Category Info Updated successfully'
              });

              this.$Progress.finish();
            })
            .catch( error => {
              this.$Progress.fail();
              console.log(error);
            });
        },

        editModal(category) {
          this.editmode = true;
          this.form.reset();
          $('#add_new_category').modal('show');
          this.form.fill(category);
        },

        newModal() {
          this.editmode = false;
          this.form.reset();
          $('#add_new_category').modal('show');
        },

        loadCategory() {
            this.$Progress.start();
            axios.get('api/category').then( response => this.categories = response.data);
            this.$Progress.finish();
        },

        createCategory() {
          this.$Progress.start();
          this.form.post('api/category')
          .then(( response ) => {

            $('#add_new_category').modal('hide');
            this.categories = response.data;

            Toast.fire({
              icon: 'success',
              title: 'Cateogry Created successfully'
            });
            this.$Progress.finish();
          })
          .catch( error => console.log(error));
        },

        deleteCategory(id) {
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
              axios.delete('api/category/' + id)
              .then( response => {
                this.categories = response.data;
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
          this.loadCategory();
      }
    }
</script>
