<x-layout>
    @include('website.partials.hero')
    <main>
        <!-- Search -->
        @include('website.partials.search')

        <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">

            @unless (count($lists) == 0)

                @foreach ($lists as $list)
                    <!-- Items -->
                    <x-listing-card :list="$list" />
                @endforeach
            @else
                <p>No Listings found </p>
            @endunless
        </div>
        <div class="mt-6 p-4">
            {{ $lists->links() }}
        </div>
    </main>
</x-layout>
