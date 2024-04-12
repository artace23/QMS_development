<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Display</title>
    <style>
      .image-container {
        background: url("/img-pic.jpg") center/cover;
      }
    </style>
  </head>
  <body class="h-screen relative justify-center items-center">
    <div class="h-full flex justify-center items-center flex-wrap p-3">
      <div
        class="w-[90%] h-[90%] mb-2 flex justify-between shadow-lg border border-slate-200 overflow-hidden"
      >
        <div class="h-full bg-white w-[60%]">
          <div class="w-[95%] sticky top-0 p-3 m-5 bg-black">
            <h1 class="text-3xl font-bold text-white">PENDING QUEUE</h1>
          </div>
          <div
            class="h-[50%] m-5 border-[5px] border-black rounded-lg overflow-hidden"
          >
            <ul class="m-3" id="ongoingQueueList">
                
            </ul>
          </div>
          <div
            class="flex items-center justify-center gap-5 p-3 h-[180px] mt-5"
          >
            <div class="w-[50%] h-[180px] image-container mb-5">
              <img
                src="/img-pic.jpg"
                alt="Image"
                class="w-full h-full object-cover"
              />
            </div>
            <div class="w-[50%] h-[180px] mb-5">
              <video controls class="w-full h-full">
                <source src="/vid.mov" type="video/quicktime" />
                Your browser does not support the video tag.
              </video>
            </div>
          </div>
        </div>
        <div class="w-[40%] bg-white overflow-hidden h-full flex flex-col">
          <div class="w-[90%] sticky top-0 p-3 m-5 bg-black">
            <h1 class="text-3xl font-bold text-white">CURRENT SERVING</h1>
          </div>
          <div
            class="w-[90%] h-full border-[5px] rounded-lg ml-3 border-black p-5 overflow-hidden"
          >
            <ul class="h-full overflow-hidden" id="currentServingList">
                
            </ul>
          </div>
        </div>
      </div>
    </div>
    <script>
      let spokenItems = new Set();
      let queue = [];
      // Function to fetch data from the server
      function fetchCurrentServingData() {
          // Send an AJAX request to the server
        fetch('/fetch-current-serving')
            .then(response => response.json())
            .then(data => {
                // Clear previous data
                document.getElementById('currentServingList').innerHTML = '';

                // Populate the list with the fetched data
                data.forEach(item => {
                    let li = document.createElement('li');
                    li.className = 'flex justify-around m-3 p-3 border border-slate-200';
                    li.innerHTML = `<p class="text-4xl font-bold">${item.queue_no}</p><p class="text-4xl font-bold">Win ${item.window_no}</p>`;
                    document.getElementById('currentServingList').appendChild(li);

                    if(!spokenItems.has(item.queue_no)) {
                      // speakText(`${item.queue_no} , window ${item.window_no}`);
                      // speakText(`${item.queue_no} , window ${item.window_no}`);
                      // spokenItems.add(item.queue_no);
                      // console.log(spokenItems);
                      queue.push(item);
                      queue.push(item);
                      spokenItems.add(item.queue_no);
                    }
                    speakNextItem();
                });
            })
            .catch(error => console.error('Error:', error));
      }

      function speakNextItem() {
          if (queue.length > 0) {
              const item = queue.shift(); // Get the next item from the queue
              speakText(`${item.queue_no} , window ${item.window_no}`);
          }
      }

      function speakText(text) {
        const utterance = new SpeechSynthesisUtterance(text);
        utterance.onend = () => {
            // Speak the next item once the current one has finished
            speakNextItem();
        };
        speechSynthesis.speak(utterance);
      }

      if ('speechSynthesis' in window) {
          fetchCurrentServingData();
      } else {
          console.error('Speech synthesis not supported.');
      }
  
      // Fetch data initially when the page loads
      fetchCurrentServingData();
  
      // Fetch data periodically every 5 seconds
      setInterval(fetchCurrentServingData, 1000);

      function fetchOngoingQueueNumbers() {
      // Send an AJAX request to the server
      fetch('/fetch-ongoing-queues')
          .then(response => response.json())
          .then(data => {
              // Clear previous data
              document.getElementById('ongoingQueueList').innerHTML = '';

              // Populate the list with the fetched data
              data.forEach(queue => {
                  let li = document.createElement('li');
                  li.className = 'text-3xl font-bold border border-slate-20 p-3 m-2';
                  li.textContent = queue.queue_no; // Accessing the queue_no property
                  document.getElementById('ongoingQueueList').appendChild(li);
              });
          })
          .catch(error => console.error('Error:', error));
      }

      // Fetch data initially when the page loads
      fetchOngoingQueueNumbers();

      // Fetch data periodically every 5 seconds
      setInterval(fetchOngoingQueueNumbers, 1000);
    </script>
  </body>
</html>