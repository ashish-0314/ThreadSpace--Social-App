<x-app-layout>
<style>
    body {
        background: #050816 url('{{ asset('images/thbg2.png') }}') no-repeat center center fixed !important;
        background-size: cover !important;
    }
    .create-split-container {
        display: flex;
        max-width: 1000px;
        width: 100%;
        background: #0d1117;
        border: 1px solid #30363d;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }
    .create-left {
        flex: 1;
        position: relative;
        display: none;
    }
    @media(min-width: 860px) {
        .create-left {
            display: block;
        }
    }
    .create-right {
        flex: 1.2;
        padding: 32px;
        display: flex;
        flex-direction: column;
    }
    @media(max-width: 600px) {
        .create-right {
            padding: 32px;
        }
    }
    .create-input {
        width: 100%;
        background: #050816;
        border: 1px solid #30363d;
        border-radius: 8px;
        padding: 10px 14px;
        color: #e1e4e8;
        font-size: 0.9rem;
        font-weight: 400;
        outline: none;
        transition: all 0.2s ease;
    }
    .create-input:focus {
        border-color: #58a6ff;
        background: #161b22;
    }
    .create-label {
        display: block;
        font-size: 0.75rem;
        font-weight: 500;
        color: #6e7681;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    .submit-btn {
        background: #f0f6fc;
        color: #050816;
        border: none;
        padding: 10px 28px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.2s;
    }
    .submit-btn:hover {
        opacity: 0.85;
    }
</style>

<div style="display: flex; justify-content: center; align-items: center; min-height: calc(100vh - 75px); padding: 40px 20px;">
    
    <div class="create-split-container">
        <!-- Left Side Image -->
        <div class="create-left">
            <img src="{{ asset('images/create_community_visual.png') }}" style="width: 100%; height: 100%; object-fit: cover; position: absolute; inset: 0;" alt="Create a Community">
            <div style="position: absolute; inset: 0; background: linear-gradient(to top, rgba(13, 17, 23, 0.9) 0%, transparent 80%); display: flex; flex-direction: column; justify-content: flex-end; padding: 32px;">
                <h2 style="color: rgba(255,255,255,0.9); font-size: 1.8rem; font-weight: 400; line-height: 1.2; margin-bottom: 8px; letter-spacing: -0.5px;">Build a space.</h2>
                <p style="color: rgba(255,255,255,0.5); font-size: 0.95rem; font-weight: 300; line-height: 1.5; margin: 0;">Gather like-minded people around shared passions and ideas.</p>
            </div>
        </div>

        <!-- Right Side Form -->
        <div class="create-right">
            <div style="margin-bottom: 20px;">
                <h1 style="font-size: 1.4rem; font-weight: 500; color: #e1e4e8; margin: 0 0 6px; letter-spacing: -0.3px;">Create a Community</h1>
                <p style="font-size: 0.85rem; font-weight: 400; color: #6e7681; margin: 0;">Start a new place for discussion on ThreadSpace.</p>
            </div>

            <form action="{{ route('communities.store') }}" method="POST" style="display: flex; flex-direction: column; gap: 16px;">
                @csrf

                <!-- Name -->
                <div>
                    <label class="create-label">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="create-input" placeholder="e.g. webdev" required autofocus>
                    <p style="font-size: 0.75rem; color: #6e7681; margin-top: 6px;">Community names cannot be changed after creation.</p>
                    @error('name')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <!-- Description -->
                <div>
                    <label class="create-label">Description</label>
                    <textarea name="description" class="create-input" rows="3" placeholder="What is this community about?" required style="resize: vertical; min-height: 60px;">{{ old('description') }}</textarea>
                    @error('description')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <!-- Rules -->
                <div>
                    <label class="create-label">Rules <span style="font-size:0.7rem;font-weight:400;color:#4b5563;text-transform:none;">(optional)</span></label>
                    <textarea name="rules" class="create-input" rows="2" placeholder="Set guidelines for your members..." style="resize: vertical; min-height: 40px;">{{ old('rules') }}</textarea>
                    @error('rules')<p style="color:#ef4444;font-size:.78rem;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <!-- Actions -->
                <div style="display:flex;justify-content:flex-end;align-items:center;gap:16px;margin-top:4px;">
                    <a href="{{ route('communities.index') }}" style="color: #6e7681; font-weight: 400; font-size: 0.9rem; text-decoration: none; transition: color 0.2s;" onmouseover="this.style.color='#c9d1d9'" onmouseout="this.style.color='#6e7681'">Cancel</a>
                    <button type="submit" class="submit-btn">Create Community</button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
