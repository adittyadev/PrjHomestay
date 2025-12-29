<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Daftar Kamar</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f5f5;
            color: #333;
        }

        .navbar {
            background: #2c3e50;
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 1.8rem;
        }

        .navbar-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.6rem 1.5rem;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .btn-login {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-login:hover {
            background: white;
            color: #2c3e50;
        }

        .btn-register {
            background: #3498db;
            color: white;
        }

        .btn-register:hover {
            background: #2980b9;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .header h2 {
            font-size: 2rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #7f8c8d;
            font-size: 1.1rem;
        }

        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .room-card {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0,0,0,0.2);
        }

        .room-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background: #ecf0f1;
        }

        .room-content {
            padding: 1.5rem;
        }

        .room-title {
            font-size: 1.4rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .room-info {
            margin-bottom: 0.8rem;
            color: #555;
        }

        .room-price {
            font-size: 1.5rem;
            color: #27ae60;
            font-weight: bold;
            margin: 1rem 0;
        }

        .status-badge {
            display: inline-block;
            padding: 0.4rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-available {
            background: #d4edda;
            color: #155724;
        }

        .status-booked {
            background: #f8d7da;
            color: #721c24;
        }

        /* Rating Section */
        .rating-summary {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 1rem 0;
            padding: 0.8rem;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .rating-number {
            font-size: 2rem;
            font-weight: bold;
            color: #f39c12;
        }

        .stars {
            color: #f39c12;
            font-size: 1.2rem;
        }

        .rating-count {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        /* Reviews Section */
        .reviews-section {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 2px solid #ecf0f1;
        }

        .reviews-header {
            font-size: 1.1rem;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .review-item {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1rem;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .reviewer-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .review-rating {
            color: #f39c12;
        }

        .review-date {
            font-size: 0.85rem;
            color: #95a5a6;
            margin-top: 0.3rem;
        }

        .review-comment {
            color: #555;
            line-height: 1.6;
            margin-top: 0.5rem;
        }

        /* Reply Section */
        .replies {
            margin-top: 1rem;
            padding-left: 1.5rem;
            border-left: 3px solid #3498db;
        }

        .reply-item {
            background: white;
            padding: 0.8rem;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }

        .reply-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.3rem;
        }

        .reply-badge {
            background: #3498db;
            color: white;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .reply-author {
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        .reply-text {
            color: #555;
            font-size: 0.9rem;
            line-height: 1.5;
        }

        .no-reviews {
            text-align: center;
            padding: 2rem;
            color: #95a5a6;
            font-style: italic;
        }

        .view-all-reviews {
            text-align: center;
            margin-top: 1rem;
        }

        .view-all-reviews a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .view-all-reviews a:hover {
            text-decoration: underline;
        }

        .no-rooms {
            text-align: center;
            padding: 3rem;
            color: #7f8c8d;
        }

        @media (max-width: 768px) {
            .rooms-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding: 1rem;
            }

            .rating-summary {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar {
                flex-direction: column;
                gap: 1rem;
                text-align: center;
            }

            .navbar-buttons {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>🏨 Sistem Booking Kamar</h1>
        <div class="navbar-buttons">
            <a href="{{ route('login') }}" class="btn btn-login">Login</a>
            <a href="{{ route('register') }}" class="btn btn-register">Register</a>
        </div>
    </nav>

    <div class="container">
        <div class="header">
            <h2>Kamar Tersedia</h2>
            <p>Pilih kamar yang sesuai dengan kebutuhan Anda</p>
        </div>

        @if($rooms->count() > 0)
            <div class="rooms-grid">
                @foreach($rooms as $room)
                    <div class="room-card">
                        <img src="{{ $room->foto_url }}" alt="{{ $room->nama_kamar }}" class="room-image">
                        
                        <div class="room-content">
                            <h3 class="room-title">{{ $room->nama_kamar }}</h3>
                            
                            <div class="room-info">
                                <strong>👥 Kapasitas:</strong> {{ $room->kapasitas }} Orang
                            </div>
                            
                            <div class="room-price">
                                {{ $room->formatted_harga }}
                            </div>
                            
                            <div>
                                <span class="status-badge {{ $room->status == 'available' ? 'status-available' : 'status-booked' }}">
                                    {{ $room->status == 'available' ? '✓ Tersedia' : '✗ Sudah Dipesan' }}
                                </span>
                            </div>

                            {{-- Rating Summary --}}
                            @if($room->reviews_count > 0)
                                <div class="rating-summary">
                                    <div class="rating-number">{{ $room->formatted_rating }}</div>
                                    <div>
                                        <div class="stars">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($room->reviews_avg_rating))
                                                    ★
                                                @else
                                                    ☆
                                                @endif
                                            @endfor
                                        </div>
                                        <div class="rating-count">{{ $room->reviews_count }} ulasan</div>
                                    </div>
                                </div>
                            @else
                                <div class="rating-summary">
                                    <div class="rating-count">Belum ada ulasan</div>
                                </div>
                            @endif

                            {{-- Reviews Section --}}
                            @if($room->reviews->count() > 0)
                                <div class="reviews-section">
                                    <div class="reviews-header">📝 Ulasan Tamu</div>
                                    
                                    @foreach($room->reviews as $review)
                                        <div class="review-item">
                                            <div class="review-header">
                                                <div>
                                                    <div class="reviewer-name">{{ $review->user->name }}</div>
                                                    <div class="review-date">{{ $review->created_at->diffForHumans() }}</div>
                                                </div>
                                                <div class="review-rating">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            ★
                                                        @else
                                                            ☆
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                            <div class="review-comment">{{ $review->comment }}</div>

                                            {{-- Replies --}}
                                            @if($review->replies->count() > 0)
                                                <div class="replies">
                                                    @foreach($review->replies as $reply)
                                                        <div class="reply-item">
                                                            <div class="reply-header">
                                                                <span class="reply-badge">BALASAN</span>
                                                                <span class="reply-author">{{ $reply->user->name }}</span>
                                                            </div>
                                                            <div class="reply-text">{{ $reply->reply }}</div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach

                                    @if($room->reviews_count > 3)
                                        <div class="view-all-reviews">
                                            <a href="#">Lihat semua {{ $room->reviews_count }} ulasan</a>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-rooms">
                <h3>Belum ada kamar yang tersedia</h3>
                <p>Silakan cek kembali nanti</p>
            </div>
        @endif
    </div>
</body>
</html>