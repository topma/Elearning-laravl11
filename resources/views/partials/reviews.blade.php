@if($reviews->isEmpty())
    <p>No reviews yet.</p>
@else
    @foreach($reviews as $review)
        <div class="students-feedback-item">
            <div class="feedback-rating">
                <div class="feedback-rating-start">
                    <div class="image">
                        <img src="{{ $review->student->image ? asset('uploads/students/' . $review->student->image) : asset('frontend/dist/images/ellipse/2.png') }}" alt="Image" />
                    </div>
                    <div class="text">
                        <h6><a href="#">{{ $review->student->name_en }}</a></h6>
                        <p>{{ $review->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

            <!-- Display rating if available -->
            @if(isset($review->rating))
                <div class="rating">
                    @for ($i = 0; $i < $review->rating; $i++)
                        <i class="fas fa-star"></i>
                    @endfor
                    @for ($i = $review->rating; $i < 5; $i++)
                        <i class="far fa-star"></i>
                    @endfor
                </div>
            @endif

            <p>{{ $review->comment }}</p>
        </div>
    @endforeach
@endif