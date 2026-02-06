<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  employee: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <Head title="Employee Details" />

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
            <li class="breadcrumb-item active" aria-current="page">Details</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="mb-0">Employee Details</h5>
              <div class="d-flex gap-2">
                <Link
                  :href="route('employees.edit', employee)"
                  class="btn btn-primary"
                >
                  <i class="bi bi-pencil-square me-2"></i>Edit
                </Link>
                <Link
                  :href="route('employees.index')"
                  class="btn btn-secondary"
                >
                  Back to List
                </Link>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-12 mb-4">
                <div class="d-flex gap-4 flex-wrap">
                  <div v-if="employee.profile_image" class="text-center">
                    <label class="form-label fw-bold d-block"
                      >Profile Image</label
                    >
                    <img
                      :src="`/storage/${employee.profile_image}`"
                      alt="Profile"
                      class="img-thumbnail"
                      style="
                        max-height: 200px;
                        max-width: 200px;
                        object-fit: cover;
                      "
                    />
                  </div>
                  <div v-if="employee.professional_image" class="text-center">
                    <label class="form-label fw-bold d-block"
                      >Professional Image</label
                    >
                    <img
                      :src="`/storage/${employee.professional_image}`"
                      alt="Professional"
                      class="img-thumbnail"
                      style="
                        max-height: 200px;
                        max-width: 200px;
                        object-fit: cover;
                      "
                    />
                  </div>
                </div>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Employee ID</label>
                <p class="mb-0">{{ employee.employee_id }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">First Name</label>
                <p class="mb-0">{{ employee.first_name }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Middle Name</label>
                <p class="mb-0">{{ employee.middle_name || "-" }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Last Name</label>
                <p class="mb-0">{{ employee.last_name }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Gender</label>
                <p class="mb-0">{{ employee.sex?.description || "-" }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Suffix</label>
                <p class="mb-0">{{ employee.suffix?.name || "-" }}</p>
              </div>
              <div class="col-md-3">
                <label class="form-label fw-bold">Office</label>
                <p class="mb-0">{{ employee.office?.name || "-" }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Division</label>
                <p class="mb-0">{{ employee.division?.name || "-" }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Unit</label>
                <p class="mb-0">
                  {{ employee.unit?.name || "-" }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
