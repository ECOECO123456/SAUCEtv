<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MovieController extends Controller
{
    /**
     * Display the catalog index view with TMDb API & master array catalog.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $selectedGenre = $request->input('genre', 'All');
        $page = (int) $request->input('page', 1);

        // TMDB Genre mappings
        $genres = [
            'All' => '',
            'Action' => '28',
            'Animation' => '16',
            'Sci-Fi' => '878',
            'TV Shows' => 'tv',
            'Thriller' => '53',
            'Comedy' => '35',
        ];

        $movies = [];
        $heroMovie = null;
        $apiKey = 'c215328e9be5d98fa65f57a66b2405a4';

        // Master local catalog array
        $masterCatalog = [
            [
                'id' => 634649, 
                'title' => 'Spider-Man: No Way Home', 
                'release_date' => '2021-12-15', 
                'vote_average' => 8.0, 
                'poster_path' => '/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/1g0dhYtq4irTY1GPXvft6k4YLjm.jpg',
                'type' => 'movie', 
                'genres' => ['Action', 'Sci-Fi', 'Comedy'],
                'overview' => 'Peter Parker is unmasked and no longer able to separate his normal life from the high-stakes of being a super-hero. When he asks for help from Doctor Strange the stakes become even more dangerous.'
            ],
            [
                'id' => 533535, 
                'title' => 'Deadpool & Wolverine', 
                'release_date' => '2024-07-24', 
                'vote_average' => 7.8, 
                'poster_path' => '/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg',
                'type' => 'movie', 
                'genres' => ['Action', 'Comedy'],
                'overview' => 'A listless Wade Wilson toils away in civilian life with his days as the morally flexible mercenary, Deadpool, behind him.'
            ],
            [
                'id' => 1241982, 
                'title' => 'Moana 2', 
                'release_date' => '2024-11-27', 
                'vote_average' => 7.0, 
                'poster_path' => '/aLVkiINlIeCkcZIzb7XHzPnHgO6.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/aLVkiINlIeCkcZIzb7XHzPnHgO6.jpg',
                'type' => 'movie', 
                'genres' => ['Animation'],
                'overview' => 'After receiving an unexpected call from her wayfinding ancestors, Moana journeys to the far seas of Oceania.'
            ],
            [
                'id' => 939243, 
                'title' => 'Sonic the Hedgehog 3', 
                'release_date' => '2024-12-19', 
                'vote_average' => 7.7, 
                'poster_path' => '/d8Ryb8AunYAuycVKDp5HpdWPKgC.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/d8Ryb8AunYAuycVKDp5HpdWPKgC.jpg',
                'type' => 'movie', 
                'genres' => ['Action', 'Animation', 'Comedy'],
                'overview' => 'Sonic, Knuckles, and Tails reunite against a powerful new adversary, Shadow.'
            ],
            [
                'id' => 299536, 
                'title' => 'Avengers: Infinity War', 
                'release_date' => '2018-04-27', 
                'vote_average' => 8.3, 
                'poster_path' => '/7WsyChLLEz331C3y9A3q3S3332.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/7WsyChLLEz331C3y9A3q3S3332.jpg',
                'type' => 'movie', 
                'genres' => ['Action', 'Sci-Fi'],
                'overview' => 'As the Avengers protect the world, Thanos emerges from cosmic shadows.'
            ],
            [
                'id' => 299534, 
                'title' => 'Avengers: Endgame', 
                'release_date' => '2019-04-26', 
                'vote_average' => 8.3, 
                'poster_path' => '/or06FN3Dka5tukK1e9sl16pB3iy.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/or06FN3Dka5tukK1e9sl16pB3iy.jpg',
                'type' => 'movie', 
                'genres' => ['Action', 'Sci-Fi'],
                'overview' => 'After devastating events, the universe lies in ruins.'
            ],
            [
                'id' => 157336, 
                'title' => 'Interstellar', 
                'release_date' => '2014-11-05', 
                'vote_average' => 8.6, 
                'poster_path' => '/gEU2Q3L3123S3332.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/gEU2Q3L3123S3332.jpg',
                'type' => 'movie', 
                'genres' => ['Sci-Fi', 'Thriller'],
                'overview' => 'Explorers travel through a wormhole in space.'
            ],
            [
                'id' => 550, 
                'title' => 'Fight Club', 
                'release_date' => '1999-10-15', 
                'vote_average' => 8.4, 
                'poster_path' => '/pB805R3iKmR9Y9P3q3S3332.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/pB805R3iKmR9Y9P3q3S3332.jpg',
                'type' => 'movie', 
                'genres' => ['Thriller', 'Action'],
                'overview' => 'An insomniac office worker forms an underground fight club.'
            ],
            [
                'id' => 1396, 
                'title' => 'Breaking Bad', 
                'release_date' => '2008-01-20', 
                'vote_average' => 8.9, 
                'poster_path' => '/ggFHVNu6YYI5L9pCfOacjizRGt.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/ggFHVNu6YYI5L9pCfOacjizRGt.jpg',
                'type' => 'tv', 
                'genres' => ['Thriller'],
                'overview' => 'A high school chemistry teacher diagnosed with inoperable lung cancer turns to manufacturing and selling methamphetamine.'
            ],
            [
                'id' => 85552, 
                'title' => 'Squid Game', 
                'release_date' => '2021-09-17', 
                'vote_average' => 7.8, 
                'poster_path' => '/dDlEmu3EZ0Pgg93K2SVNLCjCSvE.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/dDlEmu3EZ0Pgg93K2SVNLCjCSvE.jpg',
                'type' => 'tv', 
                'genres' => ['Thriller', 'Action'],
                'overview' => 'Hundreds of cash-strapped players accept a strange invitation to compete in children games.'
            ],
            [
                'id' => 926899, 
                'title' => 'Spider-Man: Beyond the Spider-Verse', 
                'release_date' => '2025-01-01', 
                'vote_average' => 8.5, 
                'poster_path' => '/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg',
                'full_poster' => 'https://image.tmdb.org/t/p/w500/8cdWjvZQUExUUTzyp4t6EDMubfO.jpg',
                'type' => 'movie', 
                'genres' => ['Animation', 'Action', 'Sci-Fi'],
                'overview' => 'Miles Morales continues his multiversal adventure in the Spider-Verse.'
            ]
        ];

        $adminPostedMovies = session()->get('admin_posted_movies', []);
        $allBaseMovies = array_merge($adminPostedMovies, $masterCatalog);

        try {
            if ($search) {
                $filteredMaster = array_filter($allBaseMovies, function($m) use ($search) {
                    return stripos($m['title'], $search) !== false || stripos($m['overview'], $search) !== false;
                });
                $res1 = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/search/multi", [
                    'api_key' => $apiKey,
                    'query' => $search,
                    'page' => $page
                ]);
                $apiResults = $res1->json()['results'] ?? [];
                $movies = array_merge(array_values($filteredMaster), $apiResults);

            } elseif ($selectedGenre === 'TV Shows') {
                $filteredLocal = array_filter($allBaseMovies, function($m) {
                    return isset($m['type']) && $m['type'] === 'tv';
                });
                $res1 = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/tv/popular", ['api_key' => $apiKey, 'page' => 1]);
                $res2 = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/tv/popular", ['api_key' => $apiKey, 'page' => 2]);
                $movies = array_merge(array_values($filteredLocal), $res1->json()['results'] ?? [], $res2->json()['results'] ?? []);

            } elseif (!empty($selectedGenre) && isset($genres[$selectedGenre]) && $genres[$selectedGenre] !== '') {
                $genreName = $selectedGenre;
                $genreId = $genres[$selectedGenre];

                $filteredLocal = array_filter($allBaseMovies, function($m) use ($genreName) {
                    return isset($m['genres']) && in_array($genreName, $m['genres']);
                });

                $res1 = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/discover/movie", [
                    'api_key' => $apiKey,
                    'with_genres' => $genreId,
                    'page' => 1
                ]);
                $res2 = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/discover/movie", [
                    'api_key' => $apiKey,
                    'with_genres' => $genreId,
                    'page' => 2
                ]);
                $movies = array_merge(array_values($filteredLocal), $res1->json()['results'] ?? [], $res2->json()['results'] ?? []);

            } else {
                $res1 = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/movie/popular", ['api_key' => $apiKey, 'page' => 1]);
                $res2 = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/movie/popular", ['api_key' => $apiKey, 'page' => 2]);
                $res3 = Http::withOptions(['verify' => false])->get("https://api.themoviedb.org/3/movie/popular", ['api_key' => $apiKey, 'page' => 3]);
                $movies = array_merge($adminPostedMovies, $masterCatalog, $res1->json()['results'] ?? [], $res2->json()['results'] ?? [], $res3->json()['results'] ?? []);
            }
        } catch (\Exception $e) {
            $movies = $allBaseMovies;
        }

        if (empty($movies)) {
            $movies = $allBaseMovies;
        }

        if (!empty($movies)) {
            $heroMovie = reset($movies);
        }

        $pendingRequestsCount = count(session()->get('user_movie_requests', []));

        return view('welcome', compact('movies', 'heroMovie', 'search', 'genres', 'selectedGenre', 'page', 'pendingRequestsCount'));
    }

    /**
     * Provide real-time autocomplete suggestions for the header search bar.
     */
    public function suggestions(Request $request)
    {
        $query = $request->input('q', '');
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $apiKey = 'c215328e9be5d98fa65f57a66b2405a4';
        $masterCatalog = [
            ['id' => 634649, 'title' => 'Spider-Man: No Way Home', 'type' => 'movie'],
            ['id' => 533535, 'title' => 'Deadpool & Wolverine', 'type' => 'movie'],
            ['id' => 1241982, 'title' => 'Moana 2', 'type' => 'movie'],
            ['id' => 939243, 'title' => 'Sonic the Hedgehog 3', 'type' => 'movie'],
            ['id' => 299536, 'title' => 'Avengers: Infinity War', 'type' => 'movie'],
            ['id' => 299534, 'title' => 'Avengers: Endgame', 'type' => 'movie'],
            ['id' => 157336, 'title' => 'Interstellar', 'type' => 'movie'],
            ['id' => 1396, 'title' => 'Breaking Bad', 'type' => 'tv']
        ];

        $adminPostedMovies = session()->get('admin_posted_movies', []);
        $allLocal = array_merge($adminPostedMovies, $masterCatalog);

        $matches = array_filter($allLocal, function($m) use ($query) {
            return stripos($m['title'], $query) !== false;
        });

        $results = [];
        foreach (array_slice($matches, 0, 5) as $m) {
            $results[] = [
                'id' => $m['id'],
                'title' => $m['title'],
                'type' => $m['type'] ?? 'movie',
                'url' => route('movie.show', ['id' => $m['id'], 'type' => $m['type'] ?? 'movie', 'title' => $m['title']])
            ];
        }

        if (count($results) < 5) {
            try {
                $response = Http::withOptions(['verify' => false])
                    ->get("https://api.themoviedb.org/3/search/multi", [
                        'api_key' => $apiKey,
                        'query' => $query
                    ]);
                $apiResults = $response->json()['results'] ?? [];
                foreach (array_slice($apiResults, 0, 5 - count($results)) as $item) {
                    $title = $item['title'] ?? $item['name'] ?? '';
                    if ($title) {
                        $type = isset($item['name']) ? 'tv' : 'movie';
                        $results[] = [
                            'id' => $item['id'],
                            'title' => $title,
                            'type' => $type,
                            'url' => route('movie.show', ['id' => $item['id'], 'type' => $type, 'title' => $title])
                        ];
                    }
                }
            } catch (\Exception $e) {}
        }

        return response()->json($results);
    }

    /**
     * Display the streaming watch page for a specific title.
     */
    public function show($id, Request $request)
    {
        $movie = [];
        $apiKey = 'c215328e9be5d98fa65f57a66b2405a4';
        $type = $request->input('type', 'movie');

        $adminPostedMovies = session()->get('admin_posted_movies', []);
        foreach ($adminPostedMovies as $apm) {
            if ($apm['id'] == $id) {
                $movie = $apm;
                break;
            }
        }

        if (empty($movie)) {
            try {
                if ($type === 'tv') {
                    $response = Http::withOptions(['verify' => false])
                        ->get("https://api.themoviedb.org/3/tv/{$id}", [
                            'api_key' => $apiKey,
                            'append_to_response' => 'credits'
                        ]);
                    $movie = $response->json();
                } else {
                    $response = Http::withOptions(['verify' => false])
                        ->get("https://api.themoviedb.org/3/movie/{$id}", [
                            'api_key' => $apiKey,
                            'append_to_response' => 'credits'
                        ]);
                    $movie = $response->json();
                }
            } catch (\Exception $e) {
                $movie = [];
            }
        }

        if (!is_array($movie) || empty($movie) || isset($movie['status_code'])) {
            $fallbackTitle = $request->input('title', 'Selected Show');
            $movie = [
                'id' => $id,
                'title' => $fallbackTitle,
                'name' => $fallbackTitle,
                'release_date' => $request->input('date', '2024'),
                'vote_average' => $request->input('rating', 8.0),
                'overview' => $request->input('overview', 'Watch ' . $fallbackTitle . ' online on SAUCEtv.'),
                'cast_names' => 'Featured Stars & Crew',
                'poster_path' => ''
            ];
        }

        $comments = session()->get("movie_comments_{$id}", []);

        return view('show', compact('movie', 'type', 'comments'));
    }

    /**
     * Show movie request submission form for users.
     */
    public function showRequestForm()
    {
        if (!session()->has('user')) {
            return redirect()->route('login')->with('error', 'Please log in to request a movie.');
        }
        return view('request_movie');
    }

    /**
     * Store submitted movie request in session.
     */
    public function storeRequestMovie(Request $request)
{
    if (!session()->has('user')) {
        return redirect()->route('login')->with('error', 'Unauthorized access.');
    }

    $request->validate([
        'title' => 'required|string|max:255',
        'type' => 'required|in:movie,tv',
        'overview' => 'nullable|string',
    ]);

    $newRequest = [
        'id' => rand(100000, 999999),
        'title' => $request->input('title'),
        'type' => $request->input('type'),
        'poster_url' => '',
        'overview' => $request->input('overview', 'Requested by user'),
        'requested_by' => session('user')['username'],
        'created_at' => now()->format('Y-m-d H:i:s'), // Eksaktong Petsa at Oras
        'status' => 'Pending'
    ];

    $requests = session()->get('user_movie_requests', []);
    array_unshift($requests, $newRequest);
    session()->put('user_movie_requests', $requests);

    return redirect()->route('home')->with('success', 'Request Submitted');
}

    /**
     * Display pending requests list for Admin.
     */
    public function showAdminRequests()
    {
        if (!session()->has('user') || session('user')['role'] !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied. Admin only.');
        }

        $requests = session()->get('user_movie_requests', []);
        return view('admin_requests', compact('requests'));
    }

    /**
     * Approve user movie request.
     */
    public function approveRequest($id, Request $request)
    {
        if (!session()->has('user') || session('user')['role'] !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied.');
        }

        $requests = session()->get('user_movie_requests', []);
        $approvedItem = null;

        foreach ($requests as $key => $req) {
            if ($req['id'] == $id) {
                $approvedItem = $req;
                unset($requests[$key]);
                break;
            }
        }
        session()->put('user_movie_requests', array_values($requests));

        if ($approvedItem) {
            $newMovie = [
                'id' => rand(100000, 999999),
                'title' => $request->input('title', $approvedItem['title']),
                'type' => $request->input('type', $approvedItem['type']),
                'full_poster' => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=500&auto=format&fit=crop&q=80',
                'overview' => $request->input('overview', $approvedItem['overview']),
                'release_date' => $request->input('release_year', '2024') . '-01-01',
                'vote_average' => (float) $request->input('rating', 8.5),
                'posted_by' => 'Admin'
            ];

            $adminMovies = session()->get('admin_posted_movies', []);
            array_unshift($adminMovies, $newMovie);
            session()->put('admin_posted_movies', $adminMovies);
        }

        return redirect()->route('admin.requests')->with('success', 'Movie request approved and published to catalog!');
    }

    /**
     * Dismiss/delete user movie request.
     */
    public function deleteRequest($id)
    {
        if (!session()->has('user') || session('user')['role'] !== 'admin') {
            return redirect()->route('home')->with('error', 'Access denied.');
        }

        $requests = session()->get('user_movie_requests', []);
        $filtered = array_filter($requests, function($r) use ($id) {
            return $r['id'] != $id;
        });
        session()->put('user_movie_requests', array_values($filtered));

        return back()->with('success', 'Request dismissed.');
    }

    /**
     * Optional Admin Post Form renderer.
     */
    public function showPostForm()
    {
        if (!session()->has('user') || session('user')['role'] !== 'admin') {
            return redirect()->route('home')->with('error', 'Only Admin can directly post movies.');
        }
        return view('post_movie');
    }

    /**
     * Process Admin direct movie post upload.
     */
    public function storePostMovie(Request $request)
    {
        if (!session()->has('user') || session('user')['role'] !== 'admin') {
            return redirect()->route('home')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:movie,tv',
            'video_file' => 'required|file|mimes:mp4,mov,avi,mkv|max:512000',
            'overview' => 'required|string',
            'release_year' => 'required|numeric',
            'rating' => 'required|numeric|min:1|max:10',
        ]);

        $videoPath = '';
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('videos'), $filename);
            $videoPath = asset('videos/' . $filename);
        }

        $newMovie = [
            'id' => rand(100000, 999999),
            'title' => $request->input('title'),
            'type' => $request->input('type'),
            'full_poster' => 'https://images.unsplash.com/photo-1536440136628-849c177e76a1?w=500&auto=format&fit=crop&q=80',
            'video_url' => $videoPath,
            'overview' => $request->input('overview'),
            'release_date' => $request->input('release_year') . '-01-01',
            'vote_average' => (float) $request->input('rating'),
            'posted_by' => 'Admin'
        ];

        $adminMovies = session()->get('admin_posted_movies', []);
        array_unshift($adminMovies, $newMovie);
        session()->put('admin_posted_movies', $adminMovies);

        return redirect()->route('home')->with('success', 'Movie and local video file successfully published!');
    }

    /**
     * Handle user comment submission for a movie.
     */
    public function addComment($id, Request $request)
    {
        if (!session()->has('user')) {
            return back()->with('error', 'Please log in to leave a review.');
        }

        $commentText = $request->input('comment');
        $rating = $request->input('rating', 8);

        if (!empty($commentText)) {
            $comments = session()->get("movie_comments_{$id}", []);
            array_unshift($comments, (object)[
                'id' => rand(1000, 9999),
                'username' => session('user')['username'],
                'comment' => $commentText,
                'rating' => $rating,
                'created_at' => now()
            ]);
            session()->put("movie_comments_{$id}", $comments);
        }

        return back();
    }

    /**
     * Delete user comment by ID for Admin.
     */
    public function deleteComment($id)
    {
        if (session()->has('user') && session('user')['role'] === 'admin') {
            foreach (session()->all() as $key => $value) {
                if (str_contains($key, 'movie_comments_')) {
                    $comments = session()->get($key, []);
                    $updatedComments = array_filter($comments, function($com) use ($id) {
                        return $com->id != $id;
                    });
                    session()->put($key, array_values($updatedComments));
                }
            }
        }
        return back();
    }

    /**
     * Remove a posted movie from catalog.
     */
    public function deleteMovie($id)
    {
        if (session()->has('user') && session('user')['role'] === 'admin') {
            $adminMovies = session()->get('admin_posted_movies', []);
            $filteredMovies = array_filter($adminMovies, function($m) use ($id) {
                return $m['id'] != $id;
            });
            session()->put('admin_posted_movies', array_values($filteredMovies));
        }
        return redirect()->route('home')->with('error', 'Movie removed from catalog.');
    }
}