<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Sale List</h3>

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
                      <th>Date</th>
                      <th>Product</th>
                      <th>Quantity</th>
                      <th>Rate</th>
                      <th>Amount</th>
                      <th>From Head</th>
                      <th>To Head</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="sale in sales.data" :key="sale.id">
                      <td>{{ sale.id }}</td>
                      <td>{{ sale.date }}</td>
                      <td>{{ sale.product.name }}</td>
                      <td>{{ sale.quantity }}</td>
                      <td>{{ sale.rate }}</td>
                      <td>{{ sale.amount }}</td>
                      <td>{{ sale.from_head.name }}</td>
                      <td>{{ sale.to_head.name }}</td>
                      <td>
                          <a href="#" @click="editModal(sale)">
                              <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="#" @click="deleteSale(sale.id)">
                              <i class="fa fa-trash red"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="sales" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="modal fade" id="add_new_sale" tabindex="-1" role="dialog" aria-labelledby="add_new_sale" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="add_new_sale">New Sale</h5>
                <h5 class="modal-title" v-show="editmode" id="add_new_sale">Update Sale Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updateSale() : createSale()">
                <div class="modal-body">
                  <div class="form-group">
                    <input v-model="form.date" type="date" name="date" id="date"
                      placeholder="date"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('date') }">
                    <has-error :form="form" field="date"></has-error>
                  </div>
                  <div class="form-group">
                    <select v-model="form.product_id" name="product_id" id="product_id"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('product_id') }" @change="sale_rate()">
                        <option value="">Select Products</option>
                        <option v-for="product in products" :key="product.id" :value="product.id">{{product.name}}</option> 
                    </select>
                    <has-error :form="form" field="product_id"></has-error>
                  </div>
                  <div class="form-group">
                    <input v-model="form.rate" type="number" step="any" name="rate" id="rate"
                      placeholder="rate"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('rate') }">
                    <has-error :form="form" field="rate"></has-error>
                  </div>
                  <div class="form-group">
                    <input v-model="form.quantity" type="number" step="any" name="quantity" id="quantity"
                      placeholder="Quantity"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('quantity') }" @keyup="calculate_amount()">
                    <has-error :form="form" field="quantity"></has-error>
                  </div>
                  <div class="form-group">
                    <input v-model="form.amount" type="number" step="any" name="amount" id="amount"
                      placeholder="amount"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('amount') }">
                    <has-error :form="form" field="amount"></has-error>
                  </div>
                  <div class="form-group">
                    <select v-model="form.from_head_id" name="from_head_id" id="from_head_id"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('from_head_id') }">
                        <option value="">Select From Head</option>
                        <option v-for="head in heads" :key="head.id" :value="head.id">{{head.name}}</option> 
                    </select>
                    <has-error :form="form" field="from_head_id"></has-error>
                  </div>
                  <div class="form-group">
                    <select v-model="form.to_head_id" name="to_head_id" id="to_head_id"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('to_head_id') }">
                        <option value="">Select To Head</option>
                        <option v-for="head in heads" :key="head.id" :value="head.id">{{head.name}}</option> 
                    </select>
                    <has-error :form="form" field="to_head_id"></has-error>
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
          products: {},
          sales: {},
          form: new Form({
            id: '',
            date: '',
            product_id: '',
            quantity: '',
            rate: '',
            amount: '',
            from_head_id: '',
            to_head_id: ''
          })
        }
      },

      methods: {
        sale_rate() {
          axios.get('/api/get_sale_rate/' + this.form.product_id)
          .then( response => {
            this.form.rate = response.data;
          })
          .catch( error => console.log(error));
        },

        calculate_amount() {
          this.form.amount = this.form.rate * this.form.quantity;
        },
        getResults(page = 1) {
          axios.get('api/sale?page=' + page)
            .then(response => {
              this.sales = response.data;
            });
        },

        loadHead() {
            axios.get('api/load_head')
            .then( response => this.heads = response.data)
        },

        
        loadProduct() {
            this.$Progress.start();
            axios.get('api/load_product').then( response => this.products = response.data);
            this.$Progress.finish();
        },

        updateSale() {
            this.$Progress.finish();
            this.form.put('api/sale/' + this.form.id)
            .then( response => {

              this.sales = response.data;
              $('#add_new_sale').modal('hide');

              Toast.fire({
                icon: 'success',
                title: 'Sale Info Updated successfully'
              });

              this.$Progress.finish();
            })
            .catch( error => {
              this.$Progress.fail();
              console.log(error);
            });
        },

        editModal(sale) {
          this.editmode = true;
          this.form.reset();
          $('#add_new_sale').modal('show');
          this.loadHead();
          this.loadProduct();
          this.form.fill(sale);
        },

        newModal() {
          this.editmode = false;
          this.form.reset();
          this.loadHead();
          this.loadProduct();
          $('#add_new_sale').modal('show');
        },

        loadSale() {
            this.$Progress.start();
            axios.get('api/sale').then( response => this.sales = response.data);
            this.$Progress.finish();
        },

        createSale() {
          this.$Progress.start();
          this.form.post('api/sale')
          .then(( response ) => {

            $('#add_new_sale').modal('hide');
            //console.log(response.data);
            this.sales = response.data;

            Toast.fire({
              icon: 'success',
              title: 'Sale Created successfully'
            });
            this.$Progress.finish();
          })
          .catch( error => console.log(error));
        },

        deleteSale(id) {
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
              axios.delete('api/sale/' + id)
              .then( response => {
                this.sales = response.data;
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
          this.loadSale();
      }
    }
</script>
