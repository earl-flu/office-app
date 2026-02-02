<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const props = defineProps({
  programs: {
    type: Array,
    default: () => [],
  },
  offices: {
    type: Array,
    default: () => [],
  },
  divisions: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  employee_id: "",
  first_name: "",
  middle_name: "",
  last_name: "",
  gender: "",
  suffix: "",
  division: "",
  program_id: "",
  office_id: "",
  profile_image: null,
  professional_image: null,
});

const onProfileImageChange = (e) => {
  form.profile_image = e.target.files?.[0] ?? null;
};

const onProfessionalImageChange = (e) => {
  form.professional_image = e.target.files?.[0] ?? null;
};

const submit = () => {
  form.post(route("employees.store"), {
    onSuccess: () => {
      const toast = useToast();
      toast.success("Employee Created Successfully", {
        timeout: 3000,
      });
    },
  });
};
</script>

<template>
  <Head title="Create Employee" />

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
            <li class="breadcrumb-item active" aria-current="page">Add</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="col-12 col-xl-8">
        <div class="card">
          <div class="card-body p-4">
            <h5 class="mb-4">Add Employee Form</h5>
            <form @submit.prevent="submit">
              <div class="row">
                <div class="col-md-4 w-full">
                  <label for="employee_id" class="form-label"
                    >Employee ID *</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    v-model="form.employee_id"
                    id="employee_id"
                    required
                  />
                  <div class="invalid-feedback d-block">
                    {{ form.errors.employee_id }}
                  </div>
                </div>
              </div>
              <div class="row g-3">
                <div class="col-md-4">
                  <label for="first_name" class="form-label"
                    >First Name *</label
                  >
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
                  <label for="middle_name" class="form-label"
                    >Middle Name</label
                  >
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
                  <label for="division_id" class="form-label">Division</label>
                  <select
                    class="form-select"
                    v-model="form.division_id"
                    id="division_id"
                  >
                    <option value="">Select Division</option>
                    <option
                      v-for="division in divisions"
                      :key="division.id"
                      :value="division.id"
                    >
                      {{ division.name }}
                    </option>
                  </select>
                  <div class="invalid-feedback d-block">
                    {{ form.errors.division_id }}
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
                  <label for="office_id" class="form-label">Office</label>
                  <select
                    class="form-select"
                    v-model="form.office_id"
                    id="office_id"
                  >
                    <option value="">Select Office</option>
                    <option
                      v-for="office in offices"
                      :key="office.id"
                      :value="office.id"
                    >
                      {{ office.name }}
                      <span v-if="office.id">
                        ({{ office.abbreviation }})
                      </span>
                    </option>
                  </select>
                  <div class="invalid-feedback d-block">
                    {{ form.errors.office_id }}
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="profile_image" class="form-label"
                    >Profile Image</label
                  >
                  <input
                    type="file"
                    class="form-control"
                    id="profile_image"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    @change="onProfileImageChange"
                  />
                  <small class="text-muted">JPEG, PNG, GIF. Max 2MB.</small>
                  <div class="invalid-feedback d-block">
                    {{ form.errors.profile_image }}
                  </div>
                </div>

                <div class="col-md-6">
                  <label for="professional_image" class="form-label"
                    >Professional Image</label
                  >
                  <input
                    type="file"
                    class="form-control"
                    id="professional_image"
                    accept="image/jpeg,image/png,image/jpg,image/gif"
                    @change="onProfessionalImageChange"
                  />
                  <small class="text-muted">JPEG, PNG, GIF. Max 2MB.</small>
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
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
