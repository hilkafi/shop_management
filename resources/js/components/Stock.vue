<template>
    <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Stock List</h3>

                <div class="card-tools">
                  <form @submit.prevent="search()"> 
                    <div class="row">
                      <div class="col-md-6">
                        <select v-model="form.product_id" name="product_id" id="product_id"
                        class="form-control">
                          <option value="">Select Product</option>
                          <option v-for="product in search_products.data" :key="product.id" :value="product.id">{{ product.name}}</option> 
                        </select>
                      </div>
                      <div class="col-md-4">
                        <input type="submit" class="form-control btn btn-primary" value="Search">
                      </div>
                    </div>
                  </form>
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
                      <th>Stock Quantity</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="product in products.data" :key="product.id">
                      <td>{{ product.id }}</td>
                      <td>{{ product.category.name }}</td>
                      <td>{{ product.name }}</td>
                      <td>{{ product.unit }}</td>
                      <td>{{ product.stocks }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
    </div>
</template>

<script>
    export default {
      data() {
        return {
          products: {},
          search_products: {},
          form: new Form({
            product_id: ''
          })
        }
      },

      methods: {

        search() {
          this.form.post('api/stock/search')
          .then( response => {
            this.products = response;
          })
          .catch( error => console.log(error));
        },

        loadProduct() {
            this.$Progress.start();
            axios.get('api/stock').then( response => {
                this.products = response;
                //console.log(this.products);
                });
            this.$Progress.finish();
        },

        load_search_products() { 
            axios.get('api/load_product/')
            .then( response =>  this.search_products = response )
            .catch(error => console.log(error));
        }

      },
      created() {
          this.loadProduct();
          this.load_search_products();
      }
    }
</script>
