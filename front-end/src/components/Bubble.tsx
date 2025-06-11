import { motion } from 'motion/react';

export default function Bubble({onClick, children}: {onClick?: CallableFunction, children: string}) {
    return (
        <motion.p
            whileHover={{scale: 1.05, transition: {duration: 0.15}}}
            whileTap={{scale: 0.95, transition: {duration: 0.05}}}
            className='bubble'
            onClick={() => onClick && onClick()}
        >{children}</motion.p>
    );
}