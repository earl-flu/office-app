<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const props = defineProps({
  facilityTypes: {
    type: Object,
    required: true,
  },
  office: {
    type: Object,
    required: true,
  },
});

const form = useForm({
  name: props.office.name,
  abbreviation: props.office.abbreviation,
  facility_type_id: props.office.facility_type_id,
  is_active: props.office.is_active,
});

const submit = () => {
  form.put(route("offices.update", props.office), {
    onSuccess: () => {
      const toast = useToast();
      toast.success("Office Updated Successfully", {
        timeout: 3000,
      });
    },
  });
};
</script>

<template>
  <Head title="Edit Office" />

  <AuthenticatedLayout>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Office</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->
    <!-- sub breadcrumb -->
    <div
      class="product-count d-flex align-items-center gap-3 gap-lg-4 mb-4 fw-bold flex-wrap font-text1"
    >
      <Link :href="route('offices.index')"
        ><span class="me-1">Offices</span></Link
      >
      <div>></div>
      <div><span class="me-1">Edit</span></div>
      <div>></div>
      <div>
        <span class="me-1">{{ office.name }}</span
        ><span class="text-secondary">({{ office.id }})</span>
      </div>
    </div>
    <!-- end sub breadcrumb -->

    <div class="row mt-5">
      <div class="col-12 col-xl-6">
        <div class="card">
          <div class="card-body p-4">
            <h5 class="mb-4">Edit Office Form</h5>
            <form class="row g-3" @submit.prevent="submit">
              <div class="col-md-12">
                <label for="name" class="form-label">Name</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.name"
                  autofocus
                  id="name"
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.name }}
                </div>
              </div>
              <!-- INSERT_YOUR_CODE -->
              <div class="col-md-12">
                <label for="abbreviation" class="form-label"
                  >Abbreviation</label
                >
                <input
                  type="text"
                  class="form-control"
                  v-model="form.abbreviation"
                  id="abbreviation"
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.abbreviation }}
                </div>
              </div>

              <div class="col-md-12">
                <label for="facility_type_id" class="form-label"
                  >Facility Type</label
                >
                <select
                  class="form-select"
                  v-model="form.facility_type_id"
                  id="facility_type_id"
                >
                  <option disabled value="">Select Facility Type</option>
                  <option
                    v-for="type in facilityTypes"
                    :key="type.id"
                    :value="type.id"
                  >
                    {{ type.name }}
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.facility_type_id }}
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="is_active"
                    v-model="form.is_active"
                  />
                  <label class="form-check-label" for="is_active">
                    Active
                  </label>
                </div>
                <div class="invalid-feedback d-block">
                  {{ form.errors.is_active }}
                </div>
              </div>

              <div class="col-md-12 mt-4">
                <div class="d-md-flex d-grid align-items-center gap-3">
                  <button
                    class="btn btn-grd btn-grd-primary px-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                  >
                    Save
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
