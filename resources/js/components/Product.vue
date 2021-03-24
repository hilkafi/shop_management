<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Product List</h3>

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
                      <th>Category</th>
                      <th>Name</th>
                      <th>Unit</th>
                      <th>Purchase Price</th>
                      <th>Sale Price</th>
                      <th>Description</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="product in products.data" :key="product.id">
                      <td>{{ product.id }}</td>
                      <td>{{ product.category_id }}</td>
                      <td>{{ product.name }}</td>
                      <td>{{ product.unit }}</td>
                      <td>{{ product.purchase_price }}</td>
                      <td>{{ product.sale_price }}</td>
                      <td>{{ product.description }}</td>
                      <td>
                          <a href="#" @click="editModal(product)">
                              <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="#" @click="deleteProduct(product.id)">
                              <i class="fa fa-trash red"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="products" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="modal fade" id="add_new_product" tabindex="-1" role="dialog" aria-labelledby="add_new_productLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="add_new_productLabel">Add New Product</h5>
                <h5 class="modal-title" v-show="editmode" id="add_new_productLabel">Update Product's Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updateProduct() : createProduct()">
                <div class="modal-body">
                  <div class="form-group">
                    <select v-model="form.category_id" name="category_id" id="category_id"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('category_id') }">
                        <option value="">Select Category</option>
                        <option v-for="category in categories" :key="category.id" :value="category.id">{{category.name}}</option> 
                    </select>
                    <has-error :form="form" field="category_id"></has-error>
                  </div>
                  <div class="form-group">
                    <input v-model="form.name" type="text" name="name" id="name"
                      placeholder="Name"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                    <has-error :form="form" field="name"></has-error>
                  </div>
                  <div class="form-group">
                    <input v-model="form.unit" type="text" name="unit" id="unit"
                      placeholder="Unit"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('unit') }">
                    <has-error :form="form" field="unit"></has-error>
                  </div>
                  <div class="form-group">
                    <input v-model="form.purchase_price" type="number" step="any" name="purchase_price" id="Purchase Price"
                      placeholder="purchase_price"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('purchase_price') }">
                    <has-error :form="form" field="purchase_price"></has-error>
                  </div>
                  <div class="form-group">
                    <input v-model="form.sale_price" type="number" step="any" name="sale_price" id="sale Price"
                      placeholder="sale_price"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('sale_price') }">
                    <has-error :form="form" field="sale_price"></has-error>
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
          products: {},
          form: new Form({
            id: '',
            category_id: '',
            name: '',
            unit: '',
            purchase_price: '',
            sale_price: '',
            description: ''
          })
        }
      },

      methods: {

        getResults(page = 1) {
          axios.get('api/product?page=' + page)
            .then(response => {
              this.products = response.data;
            });
        },

        loadCategoy() {
            axios.get('api/load_category')
            .then( response => this.categories = response.data)
        },

        updateProduct() {
            this.$Progress.finish();
            this.form.put('api/product/' + this.form.id)
            .then( response => {

              this.products = response.data;
              $('#add_new_product').modal('hide');

              Toast.fire({
                icon: 'success',
                title: 'Product Info Updated successfully'
              });

              this.$Progress.finish();
            })
            .catch( error => {
              this.$Progress.fail();
              console.log(error);
            });
        },

        editModal(product) {
          this.editmode = true;
          this.form.reset();
          $('#add_new_product').modal('show');
          this.loadCategoy();
          this.form.fill(product);
        },

        newModal() {
          this.editmode = false;
          this.form.reset();
          this.loadCategoy();
          $('#add_new_product').modal('show');
        },

        loadProduct() {
            this.$Progress.start();
            axios.get('api/product').then( response => this.products = response.data);
            this.$Progress.finish();
        },

        createProduct() {
          this.$Progress.start();
          this.form.post('api/product')
          .then(( response ) => {

            $('#add_new_product').modal('hide');
            this.products = response.data;

            Toast.fire({
              icon: 'success',
              title: 'Product Created successfully'
            });
            this.$Progress.finish();
          })
          .catch( error => console.log(error));
        },

        deleteProduct(id) {
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
              axios.delete('api/product/' + id)
              .then( response => {
                this.products = response.data;
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
          this.loadProduct();
      }
    }
</script>
