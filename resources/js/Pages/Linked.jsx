import route from 'ziggy-js'

const Linked = (props) => {
    return (
        <div className='flex flex-col items-center justify-center min-h-screen min-w-screen'>
            <h1 className='text-white text-4xl font-thin'>Link successful!</h1>
            <div>
                <a href={route('discord')}>
                    <h2 className='text-white text-2xl font-ultrathin'>Join the Discord!</h2>
                </a>
            </div>
        </div>
    )
}

export default Linked;
