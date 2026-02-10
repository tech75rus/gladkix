<template>
  <div class="bg-custom-10 min-h-screen p-4 flex flex-row">
    <Toast />
    <MenuAdmin class="md:flex" />
    <MenuMobileAdmin class="md:hidden" />
    <div class="flex-1 md:ml-[235px]">
      <slot></slot>
    </div>
  </div>
</template>