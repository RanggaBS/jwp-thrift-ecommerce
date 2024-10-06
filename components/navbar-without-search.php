<!-- -------------------------------------------------------------------- -->
<!-- Top Navbar                                                           -->
<!-- -------------------------------------------------------------------- -->

<nav class="sticky top-0 z-[2] mb-4 border-b border-zinc-300 bg-zinc-50">
  <div class="flex items-center justify-between py-3 section-wrapper">
    <?php
    include_once __DIR__ . '/brand.php';
    ?>

    <div class="flex items-center">
      <!-- <form action="" method="get" class="relative">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
              class="absolute text-transparent -translate-y-1/2 pointer-events-none top-1/2 left-4">
              <path
                d="M20 20L15.8033 15.8033M18 10.5C18 6.35786 14.6421 3 10.5 3C6.35786 3 3 6.35786 3 10.5C3 14.6421 6.35786 18 10.5 18C14.6421 18 18 14.6421 18 10.5Z"
                stroke="#000000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>

            <input type="text" name="search" id="search" placeholder="Search..."
              class="py-2 pl-12 pr-4 text-sm border rounded-full max-w-36 sm:max-w-48 md:max-w-56 border-zinc-300" />
          </form> -->

      <?php
      // include_once __DIR__ . './search.php';
      ?>

      <ul class="items-center hidden gap-4 sm:flex">
        <!-- <li>
          <a href="/wishlist.php" class="btn btn-icon !rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-heart">
              <path
                d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z" />
            </svg>
          </a>
        </li> -->
        <li>
          <a href="/cart.php" class="btn btn-icon !rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-shopping-cart">
              <circle cx="8" cy="21" r="1" />
              <circle cx="19" cy="21" r="1" />
              <path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12" />
            </svg>
          </a>
        </li>
        <li>
          <a href="/account/" class="btn btn-icon !rounded-full">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
              stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
              class="lucide lucide-user">
              <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
              <circle cx="12" cy="7" r="4" />
            </svg>
          </a>
        </li>
      </ul>
    </div>
  </div>
</nav>
