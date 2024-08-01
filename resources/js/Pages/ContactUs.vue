<script setup>
import { ref } from "vue";
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Link, useForm, Head } from '@inertiajs/vue3';

const props = defineProps(['success']);
const message = ref([props.success]);
const form = useForm({
	lead_name: '',
	lead_phone: '',
	lead_location: '',
	lead_enquiry: ''
});

const handleSubmit = () => {
	message.value = [];
	form.post(route('contactus.store'), {
		onSuccess: () => {
			form.reset();
			message.value = props.success;
		}
	});
};
</script>
<style>
main { width: 800px; }
</style>

<template>
    <Head title="Contact Us" />

    <GuestLayout>
    	<main class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-body row">
          <div class="col-5 text-center d-flex align-items-center justify-content-center">
            <div class="">
              <h2>UniSmart</h2>
              <p class="mb-5 text-left">
              	<i class="nav-icon fas fa-map-marker-alt" style="color: #e94434"></i>&nbsp;&nbsp;93, Jalan BP 6/3, Bandar Bukit <br>
              	<i class="nav-icon fas fa-none"></i> Puchong, 47120 Puchong, Selangor<br>
                <i class="nav-icon fas fa-phone"></i> +60129879936<br>
                <i class="nav-icon fab fa-whatsapp" style="color: #075E54;"></i> +60129879936<br>
                <h2></h2>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3984.409025418053!2d101.61901951065413!3d2.983873996979644!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc4d01fc5308b1%3A0x3746bf9f98a05da0!2sUni%20Smart%20Pest%20Control%20Sdn%20Bhd!5e0!3m2!1sen!2smy!4v1719205497011!5m2!1sen!2smy" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
              </p>
            </div>
          </div>
          
          <div class="col-7">

          	<div id="alert" class="alert alert-success" v-if="message[0]">Thanks for your enquiry! We'll be in touch soon.</div>

          	
          	<form @submit.prevent="handleSubmit">
	            <div class="form-group">
	              <label for="lead_name">Name</label>
	              <input type="text" id="lead_name" class="form-control" v-model="form.lead_name" />
	              <span v-if="form.errors.lead_name">{{ form.errors.lead_name }}</span>
	            </div>
	            <div class="form-group">
	              <label for="lead_phone">Phone</label>
	              <input type="text" id="lead_phone" class="form-control" v-model="form.lead_phone" />
	              <span v-if="form.errors.lead_phone">{{ form.errors.lead_phone }}</span>
	            </div>
	            <div class="form-group">
	              <label for="lead_name">Location</label>
	              <input type="text" id="lead_name" class="form-control" v-model="form.lead_location" />
	              <span v-if="form.errors.lead_location">{{ form.errors.lead_location }}</span>
	            </div>
	            <div class="form-group">
	              <label for="lead_enquiry">Enquiry</label>
	              <textarea id="lead_enquiry" class="form-control" rows="4" v-model="form.lead_enquiry"></textarea>
	              <span v-if="form.errors.lead_enquiry">{{ form.errors.lead_enquiry }}</span>
	            </div>
	            <div class="form-group">
	              <input type="submit" class="btn btn-primary" value="Send message">
	            </div>
        	</form>
          </div>
        </div>
      </div>

    </main>
    </GuestLayout>
</template>
