import cv2

# 1-video
cap1 = cv2.VideoCapture("6.1.mp4")
# 2-video

cap2 = cv2.VideoCapture("6.2.mp4")


cap3 = cv2.VideoCapture("6.3.mp4")

# Video parametrlari
fps = int(cap1.get(cv2.CAP_PROP_FPS))
width = int(cap1.get(cv2.CAP_PROP_FRAME_WIDTH))
height = int(cap1.get(cv2.CAP_PROP_FRAME_HEIGHT))

# Writer
out = cv2.VideoWriter("output.mp4", cv2.VideoWriter_fourcc(*'mp4v'), fps, (width, height))

# 1-video yozish
while True:
    ret, frame = cap1.read()
    if not ret:
        break
    out.write(frame)

# 2-video yozish
while True:
    ret, frame = cap2.read()
    if not ret:
        break
    out.write(frame)


while True:
    ret, frame = cap3.read()
    if not ret:
        break
    out.write(frame)
    
    
cap1.release()
cap2.release()
cap3.release()
out.release()
print("✅ Videolar qo‘shildi: output.mp4")
