<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const props = defineProps({
  employee: {
    type: Object,
    required: true,
  },
  programs: {
    type: Array,
    default: () => [],
  },
  facilities: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  first_name: props.employee.first_name,
  middle_name: props.employee.middle_name || "",
  last_name: props.employee.last_name,
  gender: props.employee.gender || "",
  suffix: props.employee.suffix || "",
  division: props.employee.division || "",
  program_id: props.employee.program_id || "",
  facility_id: props.employee.facility_id || "",
  profile_image: null,
  professional_image: null,
});

const profileImageUrl = () =>
  props.employee.profile_image
    ? `/storage/${props.employee.profile_image}`
    : null;
const professionalImageUrl = () =>
  props.employee.professional_image
    ? `/storage/${props.employee.professional_image}`
    : null;

const onProfileImageChange = (e) => {
  form.profile_image = e.target.files?.[0] ?? null;
};

const onProfessionalImageChange = (e) => {
  form.professional_image = e.target.files?.[0] ?? null;
};

const submit = () => {
  form.put(route("employees.update", props.employee), {
    onSuccess: () => {
      const toast = useToast();
      toast.success("Employee Updated Successfully", {
        timeout: 3000,
      });
    },
  });
};
</script>

<template>
  <Head title="Edit Employee" />

  <AuthenticatedLayout>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Employee</div>
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
      <Link :href="route('employees.index')"
        ><span class="me-1">Employees</span></Link
      >
      <div>></div>
      <div><span class="me-1">Edit</span></div>
      <div>></div>
      <div>
        <span class="me-1">{{ employee.full_name }}</span
        ><span class="text-secondary">({{ employee.employee_id }})</span>
      </div>
    </div>
    <!-- end sub breadcrumb -->

    <div class="row mt-5">
      <div class="col-12 col-xl-8">
        <div class="card">
          <div class="card-body p-4">
            <h5 class="mb-4">Edit Employee Form</h5>
            <form class="row g-3" @submit.prevent="submit">
              <div class="col-md-4">
                <label for="first_name" class="form-label">First Name *</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.first_name"
                  autofocus
                  id="first_name"
                  required
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.first_name }}
                </div>
              </div>

              <div class="col-md-4">
                <label for="middle_name" class="form-label">Middle Name</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.middle_name"
                  id="middle_name"
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.middle_name }}
                </div>
              </div>

              <div class="col-md-4">
                <label for="last_name" class="form-label">Last Name *</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.last_name"
                  id="last_name"
                  required
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.last_name }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="gender" class="form-label">Gender</label>
                <select class="form-select" v-model="form.gender" id="gender">
                  <option value="">Select Gender</option>
                  <option value="male">Male</option>
                  <option value="female">Female</option>
                  <option value="other">Other</option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.gender }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="suffix" class="form-label">Suffix</label>
                <input
                  type="text"
                  class="form-control"
                  v-model="form.suffix"
                  id="suffix"
                  placeholder="Jr., Sr., III, etc."
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.suffix }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="division" class="form-label">Division</label>
                <select
                  class="form-select"
                  v-model="form.division"
                  id="division"
                >
                  <option value="">Select Division</option>
                  <option value="HSSD">HSSD</option>
                  <option value="HSDD">HSDD</option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.division }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="program_id" class="form-label">Program</label>
                <select
                  class="form-select"
                  v-model="form.program_id"
                  id="program_id"
                >
                  <option value="">Select Program</option>
                  <option
                    v-for="program in programs"
                    :key="program.id"
                    :value="program.id"
                  >
                    {{ program.name }}
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.program_id }}
                </div>
              </div>

              <div class="col-md-6">
                <label for="facility_id" class="form-label">Facility</label>
                <select
                  class="form-select"
                  v-model="form.facility_id"
                  id="facility_id"
                >
                  <option value="">Select Facility</option>
                  <option
                    v-for="facility in facilities"
                    :key="facility.id"
                    :value="facility.id"
                  >
                    {{ facility.name }}
                    <span v-if="facility.facility_type">
                      ({{ facility.facility_type.name }})
                    </span>
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.facility_id }}
                </div>
              </div>

              <div class="col-md-6">
                <label for="profile_image" class="form-label">Profile Image</label>
                <div v-if="profileImageUrl()" class="mb-2">
                  <img
                    :src="profileImageUrl()"
                    alt="Profile"
                    class="img-thumbnail"
                    style="max-height: 120px;"
                  />
                  <p class="text-muted small mb-0 mt-1">Current image</p>
                </div>
                <input
                  type="file"
                  class="form-control"
                  id="profile_image"
                  accept="image/jpeg,image/png,image/jpg,image/gif"
                  @change="onProfileImageChange"
                />
                <small class="text-muted">JPEG, PNG, GIF. Max 2MB. Leave empty to keep current.</small>
                <div class="invalid-feedback d-block">
                  {{ form.errors.profile_image }}
                </div>
              </div>

              <div class="col-md-6">
                <label for="professional_image" class="form-label">Professional Image</label>
                <div v-if="professionalImageUrl()" class="mb-2">
                  <img
                    :src="professionalImageUrl()"
                    alt="Professional"
                    class="img-thumbnail"
                    style="max-height: 120px;"
                  />
                  <p class="text-muted small mb-0 mt-1">Current image</p>
                </div>
                <input
                  type="file"
                  class="form-control"
                  id="professional_image"
                  accept="image/jpeg,image/png,image/jpg,image/gif"
                  @change="onProfessionalImageChange"
                />
                <small class="text-muted">JPEG, PNG, GIF. Max 2MB. Leave empty to keep current.</small>
                <div class="invalid-feedback d-block">
                  {{ form.errors.professional_image }}
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
                  <Link
                    :href="route('employees.index')"
                    class="btn btn-secondary px-4"
                  >
                    Cancel
                  </Link>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
