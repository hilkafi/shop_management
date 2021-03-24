<template>
    <div class="container">
        <div class="row mt-5">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Purchase List</h3>

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
                    <tr v-for="purchase in purchases.data" :key="purchase.id">
                      <td>{{ purchase.id }}</td>
                      <td>{{ purchase.date }}</td>
                      <td>{{ purchase.product.name }}</td>
                      <td>{{ purchase.quantity }}</td>
                      <td>{{ purchase.rate }}</td>
                      <td>{{ purchase.amount }}</td>
                      <td>{{ purchase.from_head.name }}</td>
                      <td>{{ purchase.to_head.name }}</td>
                      <td>
                          <a href="#" @click="editModal(purchase)">
                              <i class="fa fa-edit blue"></i>
                          </a>
                          /
                          <a href="#" @click="deletePurchase(purchase.id)">
                              <i class="fa fa-trash red"></i>
                          </a>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <pagination :data="purchases" @pagination-change-page="getResults"></pagination>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>

        <div class="modal fade" id="add_new_purchase" tabindex="-1" role="dialog" aria-labelledby="add_new_purchaseLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" v-show="!editmode" id="add_new_purchaseLabel"> New Purchase</h5>
                <h5 class="modal-title" v-show="editmode" id="add_new_purchaseLabel">Update Purchase Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <form @submit.prevent="editmode ? updatePurchase() : createPurchase()">
                <div class="modal-body">
                  <div class="form-group">
                    <input v-model="form.date" type="date" name="date" id="date"
                      placeholder="date"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('date') }">
                    <has-error :form="form" field="date"></has-error>
                  </div>
                  <div class="form-group">
                    <select v-model="form.product_id" name="product_id" id="product_id"
                      class="form-control" :class="{ 'is-invalid': form.errors.has('product_id') }" @change="purchase_rate()">
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
          purchases: {},
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

        purchase_rate() {
          let id = this.form.product_id;
          axios.get('/api/get_purchase_rate/' + id)
          .then( response => {
            this.form.rate = response.data;
          })
          .catch( error => console.log(error));
        },

        calculate_amount() {
          this.form.amount = this.form.rate * this.form.quantity;
        },

        getResults(page = 1) {
          axios.get('api/purchase?page=' + page)
            .then(response => {
              this.products = response.data;
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

        updatePurchase() {
            this.$Progress.finish();
            this.form.put('api/purchase/' + this.form.id)
            .then( response => {

              this.purchases = response.data;
              $('#add_new_purchase').modal('hide');

              Toast.fire({
                icon: 'success',
                title: 'Purchase Info Updated successfully'
              });

              this.$Progress.finish();
            })
            .catch( error => {
              this.$Progress.fail();
              console.log(error);
            });
        },

        editModal(purchase) {
          this.editmode = true;
          this.form.reset();
          $('#add_new_purchase').modal('show');
          this.loadHead();
          this.loadProduct();
          this.form.fill(purchase);
        },

        newModal() {
          this.editmode = false;
          this.form.reset();
          this.loadHead();
          this.loadProduct();
          $('#add_new_purchase').modal('show');
        },

        loadPurchase() {
            this.$Progress.start();
            axios.get('api/purchase').then( response => this.purchases = response.data);
            this.$Progress.finish();
        },

        createPurchase() {
          this.$Progress.start();
          this.form.post('api/purchase')
          .then(( response ) => {

            $('#add_new_purchase').modal('hide');
            //console.log(response.data);
            this.purchases = response.data;

            Toast.fire({
              icon: 'success',
              title: 'Purchase Created successfully'
            });
            this.$Progress.finish();
          })
          .catch( error => console.log(error));
        },

        deletePurchase(id) {
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
              axios.delete('api/purchase/' + id)
              .then( response => {
                this.purchases = response.data;
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
          this.loadPurchase();
      }
    }
</script>
