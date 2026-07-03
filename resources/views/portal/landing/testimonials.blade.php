<section id="testimonials" class="section-padding reveal" style="background:#fff;">
    <div class="container">
        <div class="text-center mb-4">
            <div class="section-tag"><i class="fas fa-quote-left me-2"></i>Stories</div>
            <h2 class="section-heading">{{ $settings['testimonials_section_heading'] ?? 'Hear From Our Heroes' }}</h2>
            <p class="section-sub">Real stories from real donors who have made a difference in their communities.</p>
        </div>
        @if($stories->count() > 0)
            <div id="storiesCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($stories->chunk(4) as $chunkIndex => $chunk)
                        <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                            <div class="row g-3">
                                @foreach($chunk as $story)
                                    <div class="col-6 col-md-3">
                                        <div class="card border-0 shadow-sm h-100" style="border-radius:12px;background:#f8f9fa;">
                                            <div class="card-body p-3 text-center">
                                                <div class="mx-auto mb-2" style="width:48px;height:48px;border-radius:50%;overflow:hidden;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;">
                                                    @if($story->photo)
                                                        <img src="{{ asset('storage/' . $story->photo) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                                                    @else
                                                        <i class="fas fa-user text-white" style="font-size:18px;"></i>
                                                    @endif
                                                </div>
                                                <h6 class="mb-1" style="color:var(--secondary);font-weight:700;font-size:14px;">{{ $story->name }}</h6>
                                                <div style="font-size:11px;color:#888;margin-bottom:8px;">
                                                    @if($story->blood_group)<span class="badge" style="background:var(--primary);border-radius:50px;font-size:10px;color:#fff;">{{ $story->blood_group }}</span>@endif
                                                    @if($story->city)<span><i class="fas fa-map-marker-alt me-1" style="color:var(--primary);"></i>{{ $story->city }}</span>@endif
                                                </div>
                                                <i class="fas fa-quote-left" style="font-size:16px;color:rgba(220,53,69,0.15);display:block;margin-bottom:4px;"></i>
                                                <p style="font-size:12px;color:#666;line-height:1.5;margin-bottom:8px;font-style:italic;">"{{ Str::limit($story->quote, 80) }}"</p>
                                                <div style="font-size:11px;color:#999;"><i class="fas fa-tint me-1" style="color:var(--primary);"></i> {{ $story->donations_count }} donation(s)</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($stories->count() > 4)
                    <div class="text-center mt-3">
                        <button type="button" data-bs-target="#storiesCarousel" data-bs-slide="prev" class="btn btn-sm btn-outline-secondary rounded-circle me-2" style="width:36px;height:36px;"><i class="fas fa-chevron-left" style="font-size:12px;"></i></button>
                        <button type="button" data-bs-target="#storiesCarousel" data-bs-slide="next" class="btn btn-sm btn-outline-secondary rounded-circle" style="width:36px;height:36px;"><i class="fas fa-chevron-right" style="font-size:12px;"></i></button>
                    </div>
                @endif
            </div>
        @else
            <div class="text-center py-5">
                <i class="fas fa-quote-right" style="font-size:60px;color:#ddd;"></i>
                <p style="color:#999;margin-top:15px;font-size:1.1rem;">No stories yet. Be the first hero to share your experience!</p>
            </div>
        @endif
    </div>
</section>
