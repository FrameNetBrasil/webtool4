document.addEventListener('alpine:init', () => {
    window.doStore = Alpine.store('doStore', {
        dataState: '',
        timeCount: 0,
        frameCount: 1,
        timeDuration: 0,
        frameDuration: 0,
        currentVideoState: 'paused',
        currentFrame: 1,
        words: [],
        currentStartTime: 100000,
        init() {
        },
        config() {
            let config = {
                idVideoDOMElement: annotation.video.idVideo,
                fps: annotation.video.fps,
            };
        },
        updateCurrentFrame(frameNumber) {
            //console.log('updateCurrentFrame',this.currentVideoState,this.newObjectState);
            this.frameCount = this.currentFrame = frameNumber;
        },
        setWords(words) {
            this.words = words;
            this.clearSelection();
        },
        async updateWordList() {
            this.dataState = 'loading';
            await annotation.api.loadWords();
        },
        copyStartTime() {
            let time = parseInt(annotation.video.timeFromFrame(this.currentFrame) * 100) / 100;
            $("#startTime").val(time);
        },
        copyEndTime() {
            let time = parseInt(annotation.video.timeFromFrame(this.currentFrame) * 100) / 100;
            $("#endTime").val(time);
        },
        async join() {
            console.log("join");
            let words = [];
            for (var word of this.words) {
                 if (word.selected) {
                     words.push(word);
                 }
            }
            await annotation.api.joinWords({
                words
            });

            // let start = 100000;
            // let end = 0;
            // let text = "";
            // for (var word of this.words) {
            //     if (word.selected) {
            //         text = text + " " + word.word;
            //         if (word.startTime < start) {
            //             start = word.startTime;
            //         }
            //         if (word.endTime > end) {
            //             end = word.endTime;
            //         }
            //     }
            // }
            // start = parseInt(start * 100) / 100;
            // $("#startTime").val(start);
            // end = parseInt(end * 100) / 100;
            // $("#endTime").val(end);
            // $("#text").val(text);
            // $("#idOriginMM_dropdown").dropdown('set selected', 4);
            // console.log(text, start, end);
        },
        clearSelection() {
            console.log("clear selection");
            for (var word of this.words) {
                word.selected = false;
            }
            this.currentStartTime = 100000;
        },
        selectWord(wordIndex) {
            let word = this.words[wordIndex];
            console.log("select ", word.idWordMM, word.startTime, word.endTime);
            this.words[wordIndex].selected = true;
            if (word.startTime < this.currentStartTime) {
                let frame = annotation.video.frameFromTime(word.startTime);
                annotation.video.gotoFrame(frame);
                this.currentStartTime = word.startTime;
            }
        },
    });

    Alpine.effect(() => {
        const timeCount = Alpine.store('doStore').timeCount;
    });
    Alpine.effect(() => {
        const frameCount = Alpine.store('doStore').frameCount;
    });
    Alpine.effect(() => {
        const currentVideoState = Alpine.store('doStore').currentVideoState;
        if (currentVideoState === 'playing') {
        }
        if (currentVideoState === 'paused') {
        }
    });
    Alpine.effect(async () => {
        const dataState = Alpine.store('doStore').dataState;
        if (dataState === 'loaded') {
            console.log('Data Loaded');
            Alpine.store('doStore').setWords(annotation.wordList);
            Alpine.store('doStore').currentVideoState = 'paused';
        }
    });
});
