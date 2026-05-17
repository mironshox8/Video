import subprocess
import shlex

# === Parametrlar ===
video_path = "dog.mp4"
start_time = 34.350       # boshlanish vaqti (sekundlarda)
end_time = 74      # tugash vaqti (sekundlarda)
output_path = "dog.mp4"

ffmpeg_path = r"D:\qirqish\ffmpeg-2025-08-04-git-9a32b86307-full_build\ffmpeg-2025-08-04-git-9a32b86307-full_build\bin\ffmpeg.exe"  # Sizning ffmpeg.exe manzilingiz
command = f'"{ffmpeg_path}" -ss {start_time} -to {end_time} -i "{video_path}" -c copy "{output_path}" -progress - -nostats'

# === FFmpeg’ni ishga tushirish ===
process = subprocess.Popen(shlex.split(command), stdout=subprocess.PIPE, stderr=subprocess.STDOUT, text=True)

print("Kesish jarayoni boshlandi...\n")
total_duration = end_time - start_time

while True:
    output = process.stdout.readline()
    if output == '' and process.poll() is not None:
        break

    if "out_time_ms" in output:
        # FFmpegdan o‘tgan vaqtni olamiz
        time_ms = int(output.strip().split("=")[1])
        processed_sec = time_ms / 1_000_000
        progress = (processed_sec / total_duration) * 100
        print(f"\rJarayon: {progress:.2f}%", end="")

process.wait()
print("\n✅ Kesish tugadi! Fayl:", output_path)
