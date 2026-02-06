<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

// Accept `facilityTypes` as a prop from the parent (Inertia)
const props = defineProps({
  officeTypes: {
    type: Array,
    required: true,
  },
});

const form = useForm({
  name: "",
  abbreviation: "",
  office_type_id: null,
  is_active: true,
});

const submit = () => {
  form.post(route("offices.store"), {
    onSuccess: () => {
      const toast = useToast();
      toast.success("Office Saved Successfully", {
        timeout: 3000,
      });
    },
  });
};
</script>

<template>
  <Head title="Create Paper" />

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
            <li class="breadcrumb-item active" aria-current="page">Add</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="col-12 col-xl-6">
        <div class="card">
          <div class="card-body p-4">
            <h5 class="mb-4">Add Office Form</h5>
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
                <label for="office_type_id" class="form-label"
                  >Office Type</label
                >
                <select
                  id="office_type_id"
                  class="form-select"
                  v-model="form.office_type_id"
                >
                  <option value="" disabled>Select office type</option>
                  <option
                    v-for="type in officeTypes"
                    :key="type.id"
                    :value="type.id"
                  >
                    {{ type.name }}
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.office_type_id }}
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-check form-switch">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    id="is_active"
                    v-model="form.is_active"
                    :checked="form.is_active === true"
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
